<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HumanController extends Controller
{
    public function updateName(Request $request)
    {
        // Valideer de gegevens
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
        ]);

        // Zoek de 'human' op basis van het opgegeven emailadres
        $updated = DB::table('human')
            ->where('email', $request->email)  // Zoeken op basis van e-mailadres
            ->update(['name' => $request->name]);  // Werk de naam bij

        // Controleer of er een record is bijgewerkt
        if ($updated) {
            return redirect()->back()->with('success', 'Naam succesvol bijgewerkt!');
        } else {
            return redirect()->back()->with('error', 'Geen record gevonden met dat e-mailadres.');
        }
    }
}
