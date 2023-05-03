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
            'name' => 'string',
            'tax_id' => 'integer',
            'address' => 'string'
        ]);
        $user = User::find($request->user()->id);
        $user->update($validatedData);
        return response()->json($user);
    }
}
