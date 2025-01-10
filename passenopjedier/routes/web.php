<?php

use Illuminate\Support\Facades\Route;

// Functie om dummy data op te halen
function getDummyData() {
    return [
        // Oppasser info
        'sitterId' => 1,
        'sitterName' => 'Sean',
        'sitterAge' => 21,
        'sitterBeschrijving' => 'ik kom graag oppassen!',
        'sitterRating' => '5 stars',

        // Huisdier info + fk
        'hdId' => 1,
        'hdName' => 'Wolf',
        'hdAge' => 5,
        'hdRace' => 'Hond',
        'hdBeschrijving' => 'super lieve hond op zoek naar een oppasser!',
        'fkOwnerId' => 1,

        // Eigenaar info + fk
        'ownerName' => 'John',
        'ownerId' => 1,
        'fkHdId' => 1,

        // Werk info
        'hdTarief' => '20 per uur',
        'hdTime' => '4 uur',
        'hdAdres' => 'Abdis gijsbertastraat 14',
        'fkHdId' => 1,
    ];
}

// Route voor de homepagina
Route::get('/', function () {
    return "
    <a href='/home'>home</a>
    <a href='/login'>login</a>
    <a href='/register'>register</a>
    <a href='/profile'>profile</a>
    <a href='/review/write'>review</a>
    ";
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
    return view('review.write', ['dummyData' => getDummyData()]);
});

// Home pagina met dummy data
Route::get('/home', function () {
    return view('home', ['dummyData' => getDummyData()]);
});

// Profielpagina met optioneel ID
Route::get('/profile/{id?}', function ($id = null) {
    if ($id) {
        return "<a href='/'>back</a> profile of id nr: " . $id;
    } else {
        return "<a href='/'>back</a> profile";
    }
});

// Fallback route voor niet gevonden pagina's
Route::fallback(function () {
    return "Pagina niet gevonden, <a href='/'>back</a>";
});
