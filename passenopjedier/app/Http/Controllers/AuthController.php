<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        // Validate the incoming registration request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|between:8,255|confirmed',
            'haspet' => 'nullable|boolean',
            'bio' => 'nullable|string',
            'birthdate' => 'required|date',
        ]);

        $hasPet = $request->has('haspet');

        // Insert the new user into the database
        $inserted = DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'haspet' => $hasPet,
            'bio' => $request->bio,
            'birthdate' => $request->birthdate,
        ]);

        // Check if the insertion was successful
        if ($inserted) {
            // You could log the user in immediately
            Auth::attempt($request->only('email', 'password'));

            return redirect()->route('home')->with('success', 'Registration successful!');
        } else {
            return redirect()->back()->with('error', 'There was an issue with the registration.');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

}
