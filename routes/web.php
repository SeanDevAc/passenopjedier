<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('authentication.login');
})->name('opening');

Route::get('/authentication-login', function () {
    return view('authentication.login');
})->name('authentication.login');

Route::get('/authentication-register', function () {
    return view('authentication.register');
})->name('authentication.register');

Route::get('/attendant-homepage/{id}', function ($id) {
    return view('attendant.homepage');
    $html =
    "<h1> Homepage </h1>
    <div> 
        <a href='/attendant-info/1'> Zie jouw info </a>
        <a href='/job-get'> Zoek job </a>
    </div>
    id: " . $id ;
    return $html;
});

Route::get('/attendant-info/{id}', function ($id) {
    return "info of attendant id: " . $id;
});

Route::get('/consumer-homepage/{id}', function ($id) {
    $html =
    "<h1> Homepage </h1>
    <div> 
        <a href='/job-get/1'> Zie jouw jobs </a>
        <a href='/job-post/1'> Post een job </a>
    </div>
    id: " . $id ;
    return $html;
});

Route::get('/job-post/{id}', function ($id) {
    return "info of attendant id: " . $id;
});

Route::get('/job-get/{id?}', function ($id = null) {
    if ($id) {
        return "Jouw jobs: " . $id;
    } else {
        return "Alle jobs";
    };
});

Route::get('/write-review/{id}', function ($id) {
    return "review of attendant id: Dummy , Consumer id: " . $id;
});

Route::fallback(function () {
    $html =
    "<h1> Oeps... dat bestaat niet </h1>
    <div> 
        <a href='/authentication'> Terug naar start </a>
    </div>" ;
    return $html;
});