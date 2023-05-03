<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function profile(Request $request) {
        return response()->json($request->user());
    }

    public function update_profile(Request $request) {
        $validatedData = $request->validate([
            'user.name' => 'string',
            'user.tax_id' => 'integer',
            'user.address' => 'string'
        ]);
        if(!array_key_exists('user', $validatedData)){
            return response()->json([
                'status' => false,
                'message' => 'user key should be provided'
            ], 422);
        }
        $user = User::find($request->user()->id);
        $user->update($validatedData['user']);
        return response()->json($user);
    }
}
