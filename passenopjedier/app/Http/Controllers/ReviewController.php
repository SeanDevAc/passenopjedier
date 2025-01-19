<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function store(Request $request)
    {
        // Get current user
        $user = Auth::user();

        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'rating' => 'required|numeric|min:1|max:5',
            'sitterid' => 'required|exists:users,id',
            'petid' => 'required|exists:pet,petid'
        ]);

        try {
            // Insert the review
            DB::table('review')->insert([
                'description' => $validated['description'],
                'rating' => $validated['rating'],
                'placedat' => now(),
                'ownerid' => $user->id,  // Current user is the owner
                'sitterid' => $validated['sitterid'],
                'petid' => $validated['petid']
            ]);

            return redirect()->back()->with('success', 'Review succesvol geplaatst!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Er is iets misgegaan bij het plaatsen van de review.')
                ->withInput();
        }
    }
}
