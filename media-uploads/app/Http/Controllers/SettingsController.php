<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class SettingsController extends Controller
{
    public function showSettings()
    {
        $user = Auth::user();

        $decryptedToken = $user->api_token ? Crypt::decryptString($user->api_token) : null;
    
        return view('settings', [
            'apiToken' => $decryptedToken,
        ]);
    }
}
