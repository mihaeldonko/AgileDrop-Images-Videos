<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->intended('/dashboard'); 
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function regenerateApiKey(Request $request)
    {
        $user = Auth::user();
    
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
    
        $user->tokens()->delete();
    
        $plainTextToken = $user->createToken('api-token')->plainTextToken;
    
        $encryptedToken = Crypt::encryptString($plainTextToken);
    
        $user->update([
            'api_token' => $encryptedToken,
        ]);
    
        return response()->json(['api_key' => $plainTextToken], 200);
    }
    

    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if ($user->api_token) {
        $plainTextToken = Crypt::decryptString($user->api_token);

        return response()->json(['token' => $plainTextToken]);
    }

    $plainTextToken = $user->createToken('api-token')->plainTextToken;

    $encryptedToken = Crypt::encryptString($plainTextToken);

    $user->update([
        'api_token' => $encryptedToken,
    ]);

    return response()->json(['token' => $plainTextToken]);
}
}
