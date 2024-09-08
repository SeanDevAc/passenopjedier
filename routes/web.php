<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $html = 
    "<h1> Loading page </h1>
    <div> 
        <a href='/authentication'> Naar auth page
    </div>
    ";
    return $html;
});

Route::get('/authentication', function () {
    $html =
    "<h1> Authentication page </h1>
    <div> 
        <a href='/attendant-homepage/1'> Inloggen als oppasser
        <a href='/consumer-homepage/1'> Inloggen als consument
    </div>
    " ;
    return $html;
});

Route::get('/attendant-homepage/{id}', function ($id) {
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