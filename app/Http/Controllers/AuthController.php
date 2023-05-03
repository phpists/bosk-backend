<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\Token;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'user.email' => ['required', 'email', 'exists:users,email'],
            'user.password' => ['required'],
        ]);

        if (Auth::attempt($credentials['user'])) {
            $user = $request->user();

            $user->tokens->each(function (Token $token) {
                $token->revoke();
            });

            $tokenResult = $user->createToken('MyAppToken');
            $token = $tokenResult->accessToken;

            return response()
                ->json(['access_token' => $token],
                    200, [
                        'Authorization' => 'Bearer '.$token
                    ])
                ->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT, HEAD')
                ->header('Access-Control-Allow-Origin', '*')
                ->header('Access-Control-Expose-Headers', 'X-Requested-With, Content-Type, Authorization, Accept, Client-Security-Token, Accept-Encoding, iat, exp, jti')
                ->header('Access-Control-Max-Age', '7200')
                ->header('Cache-Control', 'max-age=0, private, must-revalidate')
                ->header('ETag', 'W/"4f880d9516f99b2a9b3bece71e93e2c1"')
                ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
                ->header('Vary', 'Accept')
                ->header('Vary', 'Origin')
                ->header('X-Content-Type-Options', 'nosniff')
                ->header('X-Download-Options', 'noopen')
                ->header('X-Frame-Options', 'SAMEORIGIN')
                ->header('X-Permitted-Cross-Domain-Policies', 'none')
                ->header('X-Request-Id', '840a1320-6c06-4410-8561-fa563e6e64c7')
                ->header('X-Runtime', '0.299354')
                ->header('X-XSS-Protection', '0');
        }

        return response()->json(['error' => 'Invalid request'], 422);
    }


    public function refresh_token(Request $request)
    {
        $user = $request->user();
        $user->tokens->each(function (Token $token) {
            $token->revoke();
        });
        $tokenResult = $user->createToken('MyAppToken');
        $token = $tokenResult->accessToken;

        return response()->json(['access_token' => $token], 200);

    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'user.name' => ['required', 'max:255'],
            'user.email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'user.password' => ['required', 'min:8'],
        ]);

        $data['user']['password'] = Hash::make($data['user']['password']);

        $user = new User($data['user']);

        $user->save();

        $token = $user->createToken('MyAppToken')->accessToken;

        return response()
            ->json(['status' => true, 'message' => 'success'],
                200, [
                    'Authorization' => 'Bearer '.$token
                ])->header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, DELETE, PUT, HEAD')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Expose-Headers', 'X-Requested-With, Content-Type, Authorization, Accept, Client-Security-Token, Accept-Encoding, iat, exp, jti')
            ->header('Access-Control-Max-Age', '7200')
            ->header('Cache-Control', 'max-age=0, private, must-revalidate')
            ->header('ETag', 'W/"4f880d9516f99b2a9b3bece71e93e2c1"')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->header('Vary', 'Accept')
            ->header('Vary', 'Origin')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-Download-Options', 'noopen')
            ->header('X-Frame-Options', 'SAMEORIGIN')
            ->header('X-Permitted-Cross-Domain-Policies', 'none')
            ->header('X-Request-Id', '840a1320-6c06-4410-8561-fa563e6e64c7')
            ->header('X-Runtime', '0.299354')
            ->header('X-XSS-Protection', '0');
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->tokens->each(function (Token $token) {
            $token->revoke();
        });
        return response()->json([
            'status' => true,
            'message' => 'success'
        ], 200);
    }
}
