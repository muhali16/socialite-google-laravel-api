<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Auth\OAuth2;
use Google\Service\Drive;
use Google\Service\Oauth2 as ServiceOauth2;
use Google_Client;
use http\Env\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Laravel\Socialite\Facades\Socialite;

class AuthGoogleController extends Controller
{
    public function googleAuth()
    {
        return \response()->json([
            'google_auth_url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ], 200);
    }

    public function googleAuthCallback(Request $request) : JsonResponse {
        $googleCallbackUser = Socialite::driver('google')->stateless()->user();

        $user = User::firstOrCreate([
            'social_id' => $googleCallbackUser->getId(),
            'social_type' => 'google'
        ],
        [
            'name' => $googleCallbackUser->getName(),
            'email' => $googleCallbackUser->getEmail(),
            'password' => null,
            'google_access_token_json' => 'ijn92398nfj2n3nrf293',
            'email_verified_at' => now(),
        ]);

        if ($exixtToken = $user->tokens()->where('name', 'google_auth')) {
            $exixtToken->delete();
        }

        $token = $user->createToken('google_auth', ['*'], now()->addDay())->plainTextToken;

        return \response()->json([
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    public function logout(Request $request)
    {
        if (! \auth()->user()->currentAccessToken()->delete())
        {
            return $this->error(400, "Failed delete auth token");
        }

        Auth::logout();

        return \response()->json("", 204);
    }
}
