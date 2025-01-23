<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $users = DB::table('users')->get();

        $vacancies = DB::table('vacancy')
            ->join('users as owners', 'vacancy.ownerid', '=', 'owners.id')
            ->leftJoin('pet', 'vacancy.petid', '=', 'pet.petid')
            ->leftJoin('users as sitters', 'vacancy.sitterid', '=', 'sitters.id')
            ->select(
                'vacancy.*',
                'owners.name as owner_name',
                'sitters.name as sitter_name',
                'pet.name as pet_name'
            )
            ->get();

        $applications = DB::table('applications')
            ->join('vacancy', 'applications.vacancyid', '=', 'vacancy.vacancyid')
            ->join('users as applicants', 'applications.applicantid', '=', 'applicants.id')
            ->join('users as owners', 'vacancy.ownerid', '=', 'owners.id')
            ->select(
                'applications.*',
                'applicants.name as applicant_name',
                'owners.name as owner_name',
                'vacancy.rate',
                'vacancy.datetime'
            )
            ->get();

        $reviews = DB::table('review')
            ->join('users as owners', 'review.ownerid', '=', 'owners.id')
            ->join('users as sitters', 'review.sitterid', '=', 'sitters.id')
            ->select(
                'review.*',
                'owners.name as owner_name',
                'sitters.name as sitter_name'
            )
            ->get();

        return view('admin.dashboard', compact('users', 'vacancies', 'applications', 'reviews'));
    }

    public function deleteUser($id)
    {
        try {
            DB::beginTransaction();

            // Delete related records
            DB::table('review')->where('ownerid', $id)->orWhere('sitterid', $id)->delete();
            DB::table('applications')->where('applicantid', $id)->delete();
            DB::table('vacancy')->where('ownerid', $id)->orWhere('sitterid', $id)->delete();
            DB::table('pet')->where('ownerid', $id)->delete();
            DB::table('users')->where('id', $id)->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Gebruiker succesvol verwijderd.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verwijderen van de gebruiker.');
        }
    }

    public function toggleAdmin($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();
            $currentUser = Auth::user();

            if (!$user) {
                return redirect()->back()->with('error', 'Gebruiker niet gevonden.');
            }

            if ($user->id == $currentUser->id) {
                return redirect()->back()->with('error', 'Je kunt je eigen admin-status niet wijzigen.');
            }

            DB::table('users')
                ->where('id', $id)
                ->update(['is_admin' => !$user->is_admin]);

            return redirect()->back()->with('success', 'Admin-status succesvol gewijzigd.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het wijzigen van de admin-status.');
        }
    }

    public function deleteReview($id)
    {
        try {
            DB::table('review')->where('reviewid', $id)->delete();
            return redirect()->back()->with('success', 'Review succesvol verwijderd.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verwijderen van de review.');
        }
    }

    public function deleteVacancy($id)
    {
        // Verwijder eerst gerelateerde applications
        DB::table('applications')
            ->where('vacancyid', $id)
            ->delete();

        // Verwijder dan de vacancy
        // DB::table('vacancy')
        //     ->where('vacancyid', $id)
        //     ->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Vacature en gerelateerde sollicitaties zijn verwijderd');
    }

    public function deleteApplication($id)
    {
        DB::table('applications')
            ->where('applicationid', $id)
            ->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Sollicitatie is verwijderd');
    }

    public function banUser($id)
    {
        try {
            $user = DB::table('users')->where('id', $id)->first();

            if (!$user) {
                return redirect()->back()->with('error', 'Gebruiker niet gevonden.');
            }

            if ($user->is_admin) {
                return redirect()->back()->with('error', 'Je kunt geen admin-gebruikers verbannen.');
            }

            DB::table('users')
                ->where('id', $id)
                ->update(['is_banned' => true]);

            return redirect()->back()->with('success', 'Gebruiker succesvol verbannen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het verbannen van de gebruiker.');
        }
    }

    public function unbanUser($id)
    {
        try {
            DB::table('users')
                ->where('id', $id)
                ->update(['is_banned' => false]);

            return redirect()->back()->with('success', 'Gebruiker succesvol ontbannen.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het ontbannen van de gebruiker.');
        }
    }

    public function editReview($id)
    {
        $review = DB::table('review')
            ->join('users as owners', 'review.ownerid', '=', 'owners.id')
            ->join('users as sitters', 'review.sitterid', '=', 'sitters.id')
            ->select('review.*', 'owners.name as owner_name', 'sitters.name as sitter_name')
            ->where('review.reviewid', $id)
            ->first();

        if (!$review) {
            return redirect()->route('admin.dashboard')->with('error', 'Review niet gevonden.');
        }

        return view('admin.edit-review', ['review' => $review]);
    }

    public function updateReview(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'rating' => 'required|integer|min:1|max:5',
                'description' => 'required|string|max:1000',
            ]);

            DB::table('review')
                ->where('reviewid', $id)
                ->update([
                    'rating' => $validated['rating'],
                    'description' => $validated['description']
                ]);

            return redirect()->route('admin.dashboard')->with('success', 'Review succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het bijwerken van de review.');
        }
    }

    public function editVacancy($id)
    {
        $vacancy = DB::table('vacancy')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select('vacancy.*', 'users.name as owner_name', 'pet.name as pet_name')
            ->where('vacancy.vacancyid', $id)
            ->first();

        return view('admin.edit-vacancy', ['vacancy' => $vacancy]);
    }

    public function updateVacancy(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'datetime' => 'required|date',
                'rate' => 'required|numeric|min:0',
                'description' => 'required|string|max:1000',
            ]);

            DB::table('vacancy')
                ->where('vacancyid', $id)
                ->update([
                    'datetime' => $validated['datetime'],
                    'rate' => $validated['rate'],
                    'description' => $validated['description']
                ]);

            return redirect()->route('admin.dashboard')->with('success', 'Vacature succesvol bijgewerkt.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Er is iets misgegaan bij het bijwerken van de vacature.');
        }
    }
}
