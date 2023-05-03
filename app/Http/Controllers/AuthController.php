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
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = $request->user();

            $user->tokens->each(function (Token $token) {
                $token->revoke();
            });

            $tokenResult = $user->createToken('MyAppToken');
            $token = $tokenResult->accessToken;

            return response()
                ->header('', 'Bearer '.$token)
                ->json(['access_token' => $token], 200);
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
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = new User($data);

        $user->save();

        $token = $user->createToken('MyAppToken')->accessToken;

        return response()->json(['access_token' => $token], 201);
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
