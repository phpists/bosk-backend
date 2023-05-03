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
                    ]);
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
                ]);
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
