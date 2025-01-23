<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        // ... existing middleware ...
        'admin' => \App\Http\Middleware\AdminMiddleware::class,
        // ... existing middleware ...
    ];

    // ... rest of the file ...
}
