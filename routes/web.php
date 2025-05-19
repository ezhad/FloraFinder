<?php

use App\Http\Controllers\PlantIdentifierController;
use App\Http\Integrations\CheckStatusRequest;
use App\Http\Integrations\IdentifyPlantRequest;
use App\Http\Integrations\PlantNetConnector;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;


Route::get('/', function () {

    // return redirect('/dashboard');

    return Inertia::render('Welcome');
})->name('home');


Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');

//register
Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {

        return redirect('welcome-plant');

        return Inertia::render('Dashboard');
    })->name('dashboard');



    Route::get('booking', function () {
        return Inertia::render('Booking');
    })->name('booking');




    //forum
    Route::get('forum', function () {
        return Inertia::render('ForumView');
    })->name('forum');


    //welcome plant
    Route::get('welcome-plant', function () {
        return Inertia::render('WelcomePlant');
    })->name('welcome-plant');

    Route::get('event-listener', function () {

        // get current user data
        $user = auth()->user();


        //dispatch an event
        event(new App\Events\PodcastProcessed($user));
    })->name('financial');

    Route::get('/model', function () {
        return Inertia::render('Model');
    })->name('model');



    //detect route
    // Route::get('detect', function () {
    //     return Inertia::render('Detect');
    // })->name('detect');


    Route::get(
        '/plant-identifier',
        [PlantIdentifierController::class, 'index']
    )->name('plant-identifier');
    Route::post(
        '/plant-identifier/identify',
        [PlantIdentifierController::class, 'identify']
    )->name('plant-identifier.identify');


});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
