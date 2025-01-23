<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Check if user is banned
        $user = DB::table('users')
            ->where('email', $credentials['email'])
            ->first();

        if ($user && $user->is_banned) {
            return back()
                ->withInput($request->only('email'))
                ->with('banned', true)
                ->withErrors(['email' => 'Dit account is geblokkeerd. Neem contact op met de beheerder.']);
        }

        // Attempt to log in with raw password
        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password']
        ])) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => 'De combinatie van e-mailadres en wachtwoord is niet correct.'
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/home');
    }
}
