<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HumanController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\VacancyController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\Auth\RegisterController;

Route::post('/update-name', [HumanController::class, 'updateName'])->name('update.name');
Route::post('/reviews-store', [ReviewController::class, 'store'])->name('reviews.store');
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/register', [Authcontroller::class, 'register'])->name('register');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/apply.vacancy', [VacancyController::class, 'apply'])->name('apply');

// Redirect root to home
Route::get('/', function () {
    return redirect('/home');
})->middleware('auth');

// Login pagina
Route::get('/login', function () {
    return view('auth.login');
});

// Register pagina
Route::get('/register', function () {
    return view('auth.register');
});

// Review schrijven
// Route::get('/review/write', function () { ... });

// Voeg deze nieuwe routes toe voor reviews
Route::middleware(['auth'])->group(function () {
    Route::get('/review/write/{sitter}', [ReviewController::class, 'write'])->name('review.write');
    Route::post('/reviews-store', [ReviewController::class, 'store'])->name('reviews.store');
});

// Home pagina met dummy data
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

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

// Settings routes
Route::get('/settings', function () {
    return view('settings', ['user' => Auth::user()]);
})->middleware('auth')->name('settings');

Route::put('/settings/update', [SettingsController::class, 'update'])
    ->middleware('auth')
    ->name('settings.update');

Route::put('/settings/password', [SettingsController::class, 'updatePassword'])
    ->middleware('auth')
    ->name('settings.password');

Route::delete('/settings/delete', [SettingsController::class, 'deleteAccount'])
    ->middleware('auth')
    ->name('settings.delete');

// Admin routes
Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::put('/users/{id}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::put('/users/{id}/ban', [AdminController::class, 'banUser'])->name('users.ban');
    Route::put('/users/{id}/unban', [AdminController::class, 'unbanUser'])->name('users.unban');
    Route::get('/reviews/{id}/edit', [AdminController::class, 'editReview'])->name('reviews.edit');
    Route::put('/reviews/{id}', [AdminController::class, 'updateReview'])->name('reviews.update');
    Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');
    Route::get('/vacancies/{id}/edit', [AdminController::class, 'editVacancy'])->name('vacancies.edit');
    Route::put('/vacancies/{id}', [AdminController::class, 'updateVacancy'])->name('vacancies.update');
    Route::delete('/vacancies/{id}', [AdminController::class, 'deleteVacancy'])->name('vacancies.delete');
    Route::delete('/applications/{id}', [AdminController::class, 'deleteApplication'])->name('applications.delete');
});

// Fallback route voor niet gevonden pagina's
Route::fallback(function () {
    return "Pagina niet gevonden, <a href='/'>back</a>";
});

Route::get('/home/filter', [App\Http\Controllers\HomeController::class, 'filter'])->name('home.filter');

Route::post('/applications/accept/{id}', [ApplicationController::class, 'accept'])->name('applications.accept');
Route::post('/applications/reject/{id}', [ApplicationController::class, 'reject'])->name('applications.reject');

Route::post('/appointments/cancel/{id}', [AppointmentController::class, 'cancel'])->name('appointments.cancel');

Route::get('/vacancy/edit/{id}', [VacancyController::class, 'edit'])->name('vacancy.edit');
Route::put('/vacancy/update/{id}', [VacancyController::class, 'update'])->name('vacancy.update');

Route::get('/vacancy/create', [VacancyController::class, 'create'])->name('vacancy.create');
Route::post('/vacancy/store', [VacancyController::class, 'store'])->name('vacancy.store');

Route::delete('/vacancy/delete/{id}', [VacancyController::class, 'destroy'])->name('vacancy.destroy');

Route::delete('/admin/vacancies/{id}', [AdminController::class, 'deleteVacancy'])->name('admin.vacancies.delete');
Route::delete('/admin/applications/{id}', [AdminController::class, 'deleteApplication'])->name('admin.applications.delete');

Route::get('/pet/{id}/edit', [App\Http\Controllers\PetController::class, 'edit'])->name('pet.edit');
Route::put('/pet/{id}', [App\Http\Controllers\PetController::class, 'update'])->name('pet.update');

Route::get('/pet/create', [App\Http\Controllers\PetController::class, 'create'])->name('pet.create');
Route::post('/pet', [App\Http\Controllers\PetController::class, 'store'])->name('pet.store');
Route::delete('/pet/{id}', [App\Http\Controllers\PetController::class, 'destroy'])->name('pet.destroy');

Route::post('/applications/{application}/withdraw', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');

// Route::get('/review/write//{sitter}', [ReviewController::class, 'write'])->name('review.write');
// Route::get('/register', [AuthController::class, 'showRegistrationForm']);
// Route::post('/register-form', [AuthController::class, 'register'])->name('register-form');

Route::delete('/review/{review}', [ReviewController::class, 'destroy'])->name('review.destroy')->middleware('auth');

