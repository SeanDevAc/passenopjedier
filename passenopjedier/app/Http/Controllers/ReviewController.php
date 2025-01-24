<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function write($sitter)
    {
        $user = Auth::user();

        // Haal de vacancy en gerelateerde informatie op
        $vacancy = DB::table('vacancy')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->where('vacancy.sitterid', $sitter)
            ->select('vacancy.*', 'pet.name as pet_name', 'pet.petid')
            ->first();

        if (!$vacancy) {
            return redirect()->back()->with('error', 'Geen geldige oppas gevonden.');
        }

        // Haal de oppasser informatie op
        $sitterUser = DB::table('users')
            ->where('id', $vacancy->sitterid)
            ->first();

        return view('review.write', [
            'user' => $user,
            'sitter' => $sitterUser,
            'vacancy' => $vacancy
        ]);
    }

    public function store(Request $request)
    {
        // Check of er al een review bestaat van deze eigenaar voor deze oppasser
        $existingReview = DB::table('review')
            ->where('ownerid', Auth::id())
            ->where('sitterid', $request->sitterid)
            ->first();

        if ($existingReview) {
            return redirect()->back()->with('error', 'Je hebt al een review geplaatst voor deze oppasser.');
        }

        $review = [
            'description' => $request->description,
            'rating' => $request->rating,
            'sitterid' => $request->sitterid,
            'ownerid' => Auth::id(),
            'petid' => $request->petid,
            'placedat' => now(),
        ];

        DB::table('review')->insert($review);

        return redirect()->route('home')->with('success', 'Review succesvol geplaatst!');
    }

    public function destroy($reviewId)
    {
        // Controleer of de review bestaat en van de huidige gebruiker is
        $review = DB::table('review')
            ->where('reviewid', $reviewId)
            ->where('ownerid', Auth::id())
            ->first();

        if (!$review) {
            return redirect()->back()->with('error', 'Review niet gevonden of je hebt geen rechten om deze te verwijderen.');
        }

        // Verwijder de review
        DB::table('review')
            ->where('reviewid', $reviewId)
            ->where('ownerid', Auth::id())
            ->delete();

        return redirect()->back()->with('success', 'Review succesvol verwijderd.');
    }
}
