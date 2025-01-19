<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function apply(Request $request)
    {
        $user = Auth::user();

        // Check if user has already applied
        $existingApplication = DB::table('applications')
            ->where('vacancyid', $request->vacancy_id)
            ->where('applicantid', $user->id)
            ->first();

        if ($existingApplication) {
            return redirect()->back()->with('error', 'Je hebt al gesolliciteerd op deze vacature!');
        }

        // Create new application
        DB::table('applications')->insert([
            'vacancyid' => $request->vacancy_id,
            'applicantid' => $user->id,
        ]);

        return redirect()->back()->with('success', 'Sollicitatie verstuurd!');
    }
}
