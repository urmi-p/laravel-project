<?php

namespace App\Services;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Models\Notifications;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Services\AgeVerificationInterface;

final class DiditService implements AgeVerificationInterface
{
    private $baseUrl = 'https://verification.didit.me';
    protected $apiKey;
    protected $webhookSecret;
    protected $workflowId;
    

    public function __construct()
    {
        $this->apiKey = config('settings.age_verification_didit_api_key');
        $this->webhookSecret = config('settings.age_verification_didit_webhook_secret');
        $this->workflowId = config('settings.age_verification_didit_workflow_id');
    }

    public function verify(): RedirectResponse
    {
        try {
            $client = new Client();
            $response = $client->request('POST', $this->baseUrl . '/v2/session/', [
                'json' => [
                    'workflow_id' => $this->workflowId,
                    'callback' => route('age.webhook', ['id' => auth()->id()]),
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'x-api-key' => $this->apiKey,
                ],
            ]);

            $data = json_decode((string) $response->getBody(), true);

            if ($response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
                return redirect()->away($data['url']);
            } else {
                Log::error('Error creating Didit session: ' . $data);
                return redirect()
                    ->route('verify.age')
                    ->withErrorVerification($data['detail']);
            }
        } catch (\Exception $e) {
            return redirect()
                ->route('verify.age')
                ->withErrorVerification($e->getMessage());
        }
    }

    public function webhook(Request $request): JsonResponse|RedirectResponse
    {
        try {
            $this->verifyWebhookSignature($request);

            $payload = $request->all();

            $status = $payload['status'] ?? null;

            $user = User::find($request->id);

            if ($user && $status) {
                switch (strtolower($status)) {
                    case 'Approved':
                        $user->age_verification = 1;
                        Notifications::send($user->id, 1, 37, $user->id);
                        break;

                    case 'In Review':
                        $user->age_verification = 2;
                        break;

                    case 'Declined':
                        $user->age_verification = 3;
                        Notifications::send($user->id, 1, 38, $user->id);
                        break;
                }
                $user->save();
            }
            return redirect()->route('verify.age');
        } catch (\Exception $e) {
            Log::error('Error processing Didit webhook: ' . $e->getMessage());
            return response()->json(['error' => 'Internal error'], 500);
        }
    }

    private function verifyWebhookSignature(Request $request)
    {
        $signature = $request->header('X-Didit-Signature');
        $webhookSecret = $this->webhookSecret;

        $payload = $request->getContent();
        $expectedSignature = hash_hmac('sha256', $payload, $webhookSecret);

        if (!hash_equals($expectedSignature, $signature)) {
            abort(403, 'Didit Invalid webhook signature');
        }
    }

    public function resultAgeVerification(Request $request): RedirectResponse {}
}
