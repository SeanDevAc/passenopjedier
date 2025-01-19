<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HumanController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\VacancyController;


Route::post('/update-name', [HumanController::class, 'updateName'])->name('update.name');
Route::post('/reviews-store', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/login', [Authcontroller::class, 'login'])->name('login');
Route::post('/register', [Authcontroller::class, 'register'])->name('register');
Route::post('/logout', [Authcontroller::class, 'logout'])->name('logout');
Route::post('/apply.vacancy', [VacancyController::class, 'apply'])->name('apply');

// Redirect root to home
Route::get('/', function () {
    return redirect('/home');
});

// Login pagina
Route::get('/login', function () {
    return view('auth.login');
});

// Register pagina
Route::get('/register', function () {
    return view('auth.register');
});

// Review schrijven
Route::get('/review/write', function () {
    $user = Auth::user();

    // Get all users who could be reviewed (for the dropdown)
    $users = DB::table('users')
        ->where('id', '!=', $user->id)
        ->select('id', 'name')
        ->get();

    // Get all pets (for the dropdown)
    $pets = DB::table('pet')
        ->select('petid', 'name')
        ->get();

    return view('review.write', [
        'user' => $user,
        'users' => $users,
        'pets' => $pets
    ]);
})->middleware('auth')->name('review.write');

Route::post('/reviews-store', [ReviewController::class, 'store'])
    ->name('reviews.store')
    ->middleware('auth');

// Home pagina met dummy data
Route::get('/home', function () {
    $user = Auth::user();

    $pets = DB::table('pet')
        ->where('ownerid', '=', $user->id)
        ->get();

    $vacancys = DB::table('vacancy')
        ->join('users', 'vacancy.ownerid', '=', 'users.id')
        ->join('pet', 'vacancy.petid', '=', 'pet.petid')
        ->select('vacancy.*', 'users.name as owner_name', 'pet.name as pet_name', 'pet.age as pet_age', 'pet.race as pet_race')
        ->whereNull('vacancy.sitterid')
        ->get();

    $applications = DB::table('applications')
        ->join('users', 'applications.applicantid', '=', 'users.id')
        ->join('vacancy', 'applications.vacancyid', '=', 'vacancy.vacancyid')
        ->where('vacancy.ownerid', '=', $user->id)
        ->select('applications.*', 'users.name as applicant_name', 'vacancy.rate', 'vacancy.datetime')
        ->get();

    return view('home', [
        'user' => $user,
        'pets' => $pets,
        'vacancys' => $vacancys,
        'applications' => $applications,
    ]);
})->middleware('auth');

// Profielpagina met optioneel ID
Route::get('/profile/{id?}', function ($id = null) {
    if ($id) {
        // Haal gebruiker op uit database
        $profileUser = DB::table('users')
            ->where('id', $id)
            ->first();

        // Haal reviews op voor deze gebruiker
        $review = DB::table('review')
            ->join('users', 'review.sitterid', '=', 'users.id')
            ->where('review.sitterid', $id)
            ->select('review.*', 'users.name as reviewer_name')
            ->get();

        // Haal huisdieren op van deze gebruiker
        $pets = DB::table('pet')
            ->where('ownerid', $id)
            ->get();

        return view('profile', [
            'user' => $profileUser,
            'reviews' => $review,
            'pets' => $pets
        ]);
    } else {
        // Als er geen ID is meegegeven, toon het profiel van de ingelogde gebruiker
        $user = Auth::user();
        return view('profile', ['user' => $user]);
    }
})->middleware('auth');

// Add this new route for handling applications
Route::post('/apply-vacancy', [VacancyController::class, 'apply'])
    ->name('apply.vacancy')
    ->middleware('auth');

// Fallback route voor niet gevonden pagina's
Route::fallback(function () {
    return "Pagina niet gevonden, <a href='/'>back</a>";
});
