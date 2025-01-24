<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PetController extends Controller
{
    public function edit($id)
    {
        $pet = DB::table('pet')
            ->where('petid', $id)
            ->where('ownerid', Auth::id())
            ->first();

        if (!$pet) {
            return redirect()->route('home')->with('error', 'Huisdier niet gevonden');
        }

        return view('pet.edit', compact('pet'));
    }

    public function destroy($id)
    {
        $pet = DB::table('pet')
            ->where('petid', $id)
            ->where('ownerid', Auth::id())
            ->first();

        if (!$pet) {
            return redirect()->route('home')->with('error', 'Huisdier niet gevonden of je hebt geen toestemming om dit huisdier te verwijderen.');
        }

        DB::table('pet')
            ->where('petid', $id)
            ->delete();

        // Check if user has any remaining pet
        $remainingpet = DB::table('pet')
            ->where('ownerid', Auth::id())
            ->count();

    // Check of de ingelogde gebruiker de eigenaar is
    if ($pet->ownerid !== Auth::id()) {
        return redirect()->route('home')->with('error', 'Je hebt geen toestemming om dit huisdier te verwijderen.');
    }

        return redirect()->route('home')->with('success', 'Huisdier succesvol verwijderd.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'race' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $pet = DB::table('pet')
            ->where('petid', $id)
            ->where('ownerid', Auth::id())
            ->first();

        if (!$pet) {
            return redirect()->route('home')->with('error', 'Huisdier niet gevonden');
        }

        $updateData = [
            'name' => $request->name,
            'race' => $request->race,
            'bio' => $request->bio
        ];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Convert de afbeelding naar een string met base64
            $imageData = base64_encode(file_get_contents($image->getRealPath()));

            // Update met een raw query
            DB::statement("
                UPDATE pet
                SET name = ?, race = ?, bio = ?, image = decode(?, 'base64')
                WHERE petid = ? AND ownerid = ?
            ", [
                $request->name,
                $request->race,
                $request->bio,
                $imageData,
                $id,
                Auth::id()
            ]);

            return redirect()->route('home')->with('success', 'Huisdier succesvol bijgewerkt');
        }

        // Als er geen nieuwe afbeelding is, update alleen de andere velden
        DB::table('pet')
            ->where('petid', $id)
            ->where('ownerid', Auth::id())
            ->update($updateData);

        return redirect()->route('home')->with('success', 'Huisdier succesvol bijgewerkt');
    }

    public function create()
    {
        return view('pet.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:0',
            'race' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $petData = [
            'name' => $request->name,
            'age' => $request->age,
            'race' => $request->race,
            'bio' => $request->bio,
            'ownerid' => Auth::id()
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $petData['image'] = DB::raw("decode('" .
                base64_encode(file_get_contents($image->getRealPath())) .
                "', 'base64')"
            );
        }

        DB::table('pet')->insert($petData);

        return redirect()->route('home')->with('success', 'Huisdier succesvol toegevoegd!');
    }
}
