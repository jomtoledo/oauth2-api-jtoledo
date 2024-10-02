<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to find the user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password])) {
            // Get the authenticated user
            $user = Auth::guard('web')->user();

            // Generate the access token
            $token = $user->createToken('OAuth2 App - Access Token')->accessToken;
            
            // Store the user ID and token in the session
            session(['user_id' => $user->id, 'api_token' => $token]);

            return redirect()->route('customers');
        } else {
            //return back()->withErrors(['error' => 'Unauthorized']);
            return redirect()->back()->with('error', 'Invalid credentials. Please try again.');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        // Regenerate the session token for session fixation attacks prevention
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}