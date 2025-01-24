<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ApplicationController;
use App\Models\Application;

class HomeController extends Controller
{
    public function index()
    {
        $applicationController = new ApplicationController();
        $myApplications = $applicationController->getMyApplications();

        // Haal de user op met alle data
        $user = DB::table('users')
            ->where('id', Auth::id())
            ->select('users.*')  // Selecteer alle velden, net zoals bij pets
            ->first();

        // Huisdieren van de ingelogde gebruiker
        $pets = DB::table('pet')
            ->where('ownerid', Auth::id())
            ->select(
                'pet.*'
            )
            ->get();

        // Appointments voor huisdier eigenaren
        $appointments = DB::table('vacancy')
            ->join('users', 'vacancy.sitterid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->where('vacancy.ownerid', Auth::id())
            ->whereNotNull('vacancy.sitterid')
            ->select(
                'vacancy.*',
                'pet.name as name',
                'users.name as sitter_name',
                'users.id as sitterid'
            )
            ->get();

        // Ontvangen sollicitaties
        $applications = DB::table('applications')
            ->join('vacancy', 'applications.vacancyid', '=', 'vacancy.vacancyid')
            ->join('users', 'applications.applicantid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->where('vacancy.ownerid', Auth::id())
            ->select(
                'applications.*',
                'pet.name as pet_name',
                'users.name as applicant_name',
                'users.id as applicantid',
                'vacancy.rate',
                'vacancy.datetime'
            )
            ->get();

        $users = DB::table('users')->get();

        // Open vacatures van andere gebruikers
        $vacancys = DB::table('vacancy')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'vacancy.*',
                'users.name as owner_name',
                'pet.name as pet_name'
            )
            ->whereNull('vacancy.sitterid')
            ->where('vacancy.ownerid', '!=', $user->id)
            ->get();

        // Eigen open vacatures
        $ownVacancies = DB::table('vacancy')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'vacancy.*',
                'users.name as owner_name',
                'pet.name as pet_name'
            )
            ->whereNull('vacancy.sitterid')
            ->where('vacancy.ownerid', $user->id)
            ->get();

        return view('home', compact('users', 'user', 'vacancys', 'myApplications', 'ownVacancies', 'pets', 'appointments', 'applications'));
    }

    public function filter(Request $request)
    {
        $query = DB::table('vacancy')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->select(
                'vacancy.*',
                'users.name as owner_name'
            );

        if ($request->filled('name')) {
            $searchTerm = '%' . strtolower($request->name) . '%';
            $query->whereRaw('LOWER(users.name) LIKE ?', [$searchTerm]);
            // Debug log
            \Illuminate\Support\Facades\Log::info('Search term: ' . $searchTerm);
            \Illuminate\Support\Facades\Log::info('SQL Query: ' . $query->toSql());
        }

        if ($request->filled('price')) {
            $query->where('rate', '>=', $request->price);
        }

        $vacancys = $query->get();
        // Debug log
        \Illuminate\Support\Facades\Log::info('Number of results: ' . count($vacancys));
        foreach ($vacancys as $vacancy) {
            \Illuminate\Support\Facades\Log::info('Found vacancy with owner: ' . $vacancy->owner_name);
        }

        $users = DB::table('users')->get();
        $user = Auth::user();

        // Ontvangen sollicitaties
        $applications = DB::table('applications')
            ->join('users', 'applications.applicantid', '=', 'users.id')
            ->join('vacancy', 'applications.vacancyid', '=', 'vacancy.vacancyid')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'applications.*',
                'users.name as applicant_name',
                'vacancy.rate',
                'vacancy.datetime',
                'pet.name as pet_name'
            )
            ->where('vacancy.ownerid', $user->id)
            ->get();

        $ownVacancies = DB::table('vacancy')
            ->join('users', 'vacancy.ownerid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'vacancy.*',
                'users.name as owner_name',
                'pet.name as pet_name'
            )
            ->whereNull('vacancy.sitterid')
            ->where('vacancy.ownerid', $user->id)
            ->get();

        // Gemaakte afspraken
        $appointments = DB::table('vacancy')
            ->join('users', 'vacancy.sitterid', '=', 'users.id')
            ->join('pet', 'vacancy.petid', '=', 'pet.petid')
            ->select(
                'vacancy.*',
                'users.name as sitter_name',
                'pet.name as pet_name'
            )
            ->where('vacancy.ownerid', $user->id)
            ->whereNotNull('vacancy.sitterid')
            ->get();

        // Voeg ook de pet toe aan de filter methode
        $pet = DB::table('pet')
            ->where('ownerid', Auth::user()->id)
            ->get();

        return view('home', compact('users', 'user', 'vacancys', 'applications', 'appointments', 'pet', 'ownVacancies'));
    }

//     public function __invoke()
// {
//     // $user = auth()->user();

//     if (!$user) {
//         // Redirect to login or show an error message
//         return redirect()->route('login')->with('error', 'You need to log in.');
//     }

//     return view('home', ['user' => $user]);
// }


}
