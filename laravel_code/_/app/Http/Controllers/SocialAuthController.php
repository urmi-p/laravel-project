<?php

namespace App\Http\Controllers;

use App\Services\SocialAccountService;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
  protected function driver($provider, $callback = false)
  {
    $driver = Socialite::driver($provider);

    if ($provider === 'apple' && $callback) {
      $driver = $driver->stateless();
    }

    return $driver;
  }

  // Redirect function
  public function redirect($provider)
  {
    return $this->driver($provider)->redirect();
  }
  // Callback function
  public function callback(SocialAccountService $service, $provider)
  {
    try {
      $providerUser = $this->driver($provider, true)->user();

      $user = $service->createOrGetUser($providerUser, $provider);

      // Return Error missing Email User
      if (!isset($user->id)) {
        return $user;
      } else {
        auth()->login($user);
      }
    } catch (\Exception $e) {
      return redirect('login')->with(['error_social_login' => $e->getMessage()]);
    }

    return redirect()->to('/');
  }
}
