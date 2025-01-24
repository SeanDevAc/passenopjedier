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

    public function create()
    {
        $user = Auth::user();
        $pets = DB::table('pet')
            ->where('ownerid', $user->id)
            ->get();

        return view('vacancy.create', compact('pets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
            'datetime' => 'required|date',
            'duration' => 'required|numeric|min:1',
            'description' => 'required|string|max:1000',
            'pet_id' => 'required|exists:pet,petid'
        ]);

        DB::table('vacancy')->insert([
            'ownerid' => Auth::id(),
            'petid' => $request->pet_id,
            'rate' => $request->rate,
            'datetime' => $request->datetime,
            'duration' => $request->duration,
            'description' => $request->description,
            'placedat' => now(),
        ]);

        return redirect()->route('home')->with('success', 'Vacature succesvol aangemaakt');
    }

    public function edit($id)
    {
        $vacancy = DB::table('vacancy')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select('vacancy.*', 'pet.name as pet_name')
            ->where('vacancy.vacancyid', $id)
            ->first();

        $pets = DB::table('pet')
            ->where('ownerid', Auth::id())
            ->get();

        return view('vacancy.edit', compact('vacancy', 'pets'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'rate' => 'required|numeric|min:0',
            'datetime' => 'required|date',
            'duration' => 'required|numeric|min:1',
            'description' => 'required|string|max:1000',
            'pet_id' => 'required|exists:pet,petid'
        ]);

        DB::table('vacancy')
            ->where('vacancyid', $id)
            ->update([
                'petid' => $request->pet_id,
                'rate' => $request->rate,
                'datetime' => $request->datetime,
                'duration' => $request->duration,
                'description' => $request->description,
            ]);

        return redirect()->route('home')->with('success', 'Vacature succesvol bijgewerkt');
    }

    public function destroy($id)
    {
        DB::table('vacancy')->where('vacancyid', $id)->delete();
        return redirect()->route('home')->with('success', 'Vacature succesvol verwijderd');
    }
}
