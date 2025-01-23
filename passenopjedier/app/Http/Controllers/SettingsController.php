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

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'birthdate' => 'nullable|date|before:today',
            'bio' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Handle profile image upload
            if ($request->hasFile('profile_image')) {
                // Delete old image if it exists
                if ($user->profile_image && Storage::exists('public/profile_images/' . $user->profile_image)) {
                    Storage::delete('public/profile_images/' . $user->profile_image);
                }

                // Store new image
                $imageName = time() . '.' . $request->profile_image->extension();
                $request->profile_image->storeAs('public/profile_images', $imageName);
                $validated['profile_image'] = $imageName;
            }

            // Update user in database
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'birthdate' => $validated['birthdate'],
                    'bio' => $validated['bio'],
                    'profile_image' => $validated['profile_image'] ?? $user->profile_image,
                    'updated_at' => now()
                ]);

            return redirect()
                ->back()
                ->with('success', 'Profiel succesvol bijgewerkt!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Er is iets misgegaan bij het bijwerken van je profiel.')
                ->withInput();
        }
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
                    'updated_at' => now()
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
            if ($user->profile_image && Storage::exists('public/profile_images/' . $user->profile_image)) {
                Storage::delete('public/profile_images/' . $user->profile_image);
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
