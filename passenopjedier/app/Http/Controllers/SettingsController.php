<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password;

class SettingsController extends Controller
{
    public function index()
    {
        return view('settings', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . Auth::id(),
            'bio' => 'nullable|string',
            'profileimage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:8|confirmed',
            'birthdate' => 'required|date',
            'haspet' => 'required|boolean',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'bio' => $request->bio,
            'birthdate' => $request->birthdate,
            'haspet' => $request->haspet,
        ];

        // Handle profile image
        if ($request->hasFile('profileimage')) {
            $image = $request->file('profileimage');
            $updateData['profileimage'] = DB::raw("decode('" .
                base64_encode(file_get_contents($image->getRealPath())) .
                "', 'base64')"
            );
        }

        // Als er een nieuw wachtwoord is, voeg deze toe
        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'Het huidige wachtwoord is incorrect.']);
            }
            $updateData['password'] = Hash::make($request->new_password);
        }

        // Update database
        DB::table('users')
            ->where('id', Auth::id())
            ->update($updateData);

        return redirect()->route('settings')->with('success', 'Instellingen succesvol bijgewerkt!');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => ['required', 'confirmed', Password::min(8)
                ->mixedCase()
                ->numbers()
                ->symbols()
            ],
        ]);

        try {
            // Update password in database
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'password' => Hash::make($validated['new_password']),
                ]);

            return redirect()
                ->back()
                ->with('success', 'Wachtwoord succesvol gewijzigd!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Er is iets misgegaan bij het wijzigen van je wachtwoord.');
        }
    }

    public function deleteAccount(Request $request)
    {
        $user = Auth::user();

        // Validate the confirmation
        $request->validate([
            'delete_confirmation' => 'required|in:VERWIJDER'
        ]);

        try {
            // Begin transaction
            DB::beginTransaction();

            // Delete user's profile image if it exists
            if ($user->profileimage && Storage::exists('public/profileimages/' . $user->profileimage)) {
                Storage::delete('public/profileimages/' . $user->profileimage);
            }

            // Delete related records
            DB::table('review')->where('ownerid', $user->id)->orWhere('sitterid', $user->id)->delete();
            DB::table('applications')->where('applicantid', $user->id)->delete();
            DB::table('vacancy')->where('ownerid', $user->id)->orWhere('sitterid', $user->id)->delete();
            DB::table('pet')->where('ownerid', $user->id)->delete();

            // Finally, delete the user
            DB::table('users')->where('id', $user->id)->delete();

            // Commit transaction
            DB::commit();

            // Logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('success', 'Je account is succesvol verwijderd.');

        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Er is iets misgegaan bij het verwijderen van je account.');
        }
    }
}
