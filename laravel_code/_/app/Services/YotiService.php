<?php

namespace App\Services;

use App\Models\Notifications;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use phpseclib3\Crypt\RSA;

class YotiService implements AgeVerificationInterface
{
    protected $sdkId;
    protected $apiKey;

    public function __construct()
    {
        $this->sdkId = config('settings.age_verification_yoti_sdk_id');
        $this->apiKey = config('settings.age_verification_yoti_api_key');
    }

    public function verify(): RedirectResponse
    {
        try {
            $client = new Client();

            $payload = [
                'type' => 'OVER',
                'ttl' => 300,
                'age_estimation' => [
                    'allowed' => true,
                    'threshold' => config('settings.threshold_age_verification'),
                    'level' => 'PASSIVE',
                    'retry_limit' => 1,
                ],
                'digital_id' => [
                    'allowed' => true,
                    'threshold' => 18,
                    'age_estimation_allowed' => true,
                    'age_estimation_threshold' => config('settings.threshold_age_verification'),
                    'level' => 'NONE',
                    'retry_limit' => 1,
                ],
                'doc_scan' => [
                    'allowed' => true,
                    'threshold' => 18,
                    'authenticity' => 'AUTO',
                    'level' => 'PASSIVE',
                    'retry_limit' => 1,
                ],
                'notification_url' => route('age.webhook', ['id' => auth()->id()]),
                'callback' => [
                    'auto' => true,
                    'url' => route('age.verification.result', ['id' => auth()->id()]),
                ],
                'cancel_url' => route('verify.age'),
                'synchronous_checks' => true,
            ];

            $response = $client->post('https://age.yoti.com/api/v1/sessions', [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Yoti-SDK-Id' => $this->sdkId,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $sessionId = $data['id'] ?? null;
            $redirectUrl = "https://age.yoti.com?sessionId={$sessionId}&sdkId={$this->sdkId}";

            return redirect()->away($redirectUrl);
        } catch (\Exception $e) {
            return redirect()
                ->route('verify.age')
                ->withErrorVerification($e->getMessage());
        }
    }

    public function resultAgeVerification(Request $request): RedirectResponse
    {
        try {
            $client = new Client();
            $response = $client->get("https://age.yoti.com/api/v1/sessions/{$request->sessionId}/result", [
                'headers' => [
                    'Authorization' => "Bearer {$this->apiKey}",
                    'Yoti-SDK-Id' => $this->sdkId,
                    'Content-Type' => 'application/json',
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);
            $user = User::find($request->id);

            if ($user) {
                switch (strtoupper((string) ($data['status'] ?? ''))) {
                    case 'COMPLETE':
                        $user->age_verification = 1;
                        Notifications::send($user->id, 1, 37, $user->id);
                        break;

                    case 'IN_PROGRESS':
                        $user->age_verification = 2;
                        break;

                    case 'FAIL':
                        $user->age_verification = 3;
                        Notifications::send($user->id, 1, 38, $user->id);
                        break;
                }

                $user->save();
            }

            return redirect()->route('verify.age');
        } catch (\Exception $e) {
            return redirect()->route('verify.age')->withErrorVerification($e->getMessage());
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        $body = $request->getContent();
        $json = json_decode($body, true);

        if (!is_array($json)) {
            abort(403, 'Webhook Age Verification - Invalid payload');
        }

        $signature = $json['signature'] ?? '';

        if ($signature === '') {
            info('ERROR: Webhook Age Verification - Signature not found');
            abort(403, 'Webhook Age Verification - Signature not found');
        }

        if (!$this->verifyYotiSignature($body, $signature)) {
            info('ERROR: webhook - verifyYotiSignature - Invalid signature');
            abort(403, 'Invalid signature');
        }

        $user = User::find($request->id);
        $state = strtoupper((string) ($json['state'] ?? $json['status'] ?? ''));

        if ($user) {
            switch ($state) {
                case 'COMPLETE':
                    $user->age_verification = 1;
                    Notifications::send($user->id, 1, 37, $user->id);
                    break;

                case 'IN_PROGRESS':
                    $user->age_verification = 2;
                    break;

                case 'FAIL':
                    $user->age_verification = 3;
                    Notifications::send($user->id, 1, 38, $user->id);
                    break;
            }

            $user->save();
        }

        return response()->json(['ok' => true]);
    }

    protected function verifyYotiSignature(string $body, string $signature): bool
    {
        $publicKeyPath = public_path('admin/public-key.pem');

        if (!file_exists($publicKeyPath)) {
            info('ERROR: verifyYotiSignature - Public key file not found');

            return false;
        }

        $data = json_decode($body, true);

        if (!is_array($data)) {
            info('ERROR: verifyYotiSignature - Invalid JSON payload');

            return false;
        }

        unset($data['sequence_number'], $data['signature']);

        $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $jsonString = str_replace(' ', '', $jsonString);

        $signatureDecoded = base64_decode($signature, true);

        if ($signatureDecoded === false) {
            info('ERROR: verifyYotiSignature - Invalid base64 signature');

            return false;
        }

        $signatureLengthBytes = strlen($signatureDecoded);
        $digestOutputLength = 32;
        $saltLength = $signatureLengthBytes - $digestOutputLength - 2;

        $verified = RSA::loadPublicKey(file_get_contents($publicKeyPath))
            ->withPadding(RSA::SIGNATURE_PSS)
            ->withHash('sha256')
            ->withSaltLength($saltLength)
            ->verify($jsonString, $signatureDecoded);

        if ($verified) {
            return true;
        }

        info('ERROR: verifyYotiSignature - Signature Verified: false');

        return false;
    }
}
