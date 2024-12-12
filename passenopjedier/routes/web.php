<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return "hello world";
});

Route::get('/authentication', function () {
    return "Authentication page";
});

Route::get('/home', function () {
    return "Home page ";
});

Route::get('/profile', function () {
    return "Insight in user profile";
});
