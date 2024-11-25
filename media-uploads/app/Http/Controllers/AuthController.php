<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        if ($user->api_token) {
            return response()->json(['token' => $user->api_token]);
        }

        $plainTextToken = $user->createToken('api-token')->plainTextToken;

        $user->update([
            'api_token' => $plainTextToken,
        ]);

        return response()->json(['token' => $plainTextToken]);
    }    
}
