<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('public_html.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () { //ici on créé les routes qui sont protégés cvd si je tape l'url teams/create lorsqsue je suis pas logged le système va me diriger vers la page login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('teams', TeamController::class); //un seul route fait l'appel a tous les fonction du contoleur product
    Route::resource('projects', ProjectController::class); //un seul route fait l'appel a tous les fonction du contoleur product
    Route::resource('tasks', TaskController::class); //un seul route fait l'appel a tous les fonction du contoleur product
    Route::get('/teams/{team}/members', [TeamController::class, 'members'])->name('teams.members');

});

// Routes pour l'authentification sociale
Route::get('auth/{driver}/redirect', [SocialLoginController::class, 'redirectToProvider'])->where('driver', 'google|facebook');
Route::get('auth/{driver}/login', [SocialLoginController::class, 'handleProviderCallback'])->where('driver', 'google|facebook');
Route::get('/sendEmail', [EmailVeriController::class, 'send']);

// routes/web.php


require __DIR__.'/auth.php';

