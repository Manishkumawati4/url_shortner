<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ShortUrlController;
use App\Http\Controllers\InvitationController;

// Route::get('/', function () {
//     return view('welcome');
// }); 

Route::middleware('guest')->group(function () {

    Route::get('/', [AuthController::class, 'showLogin'])->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');
        
    // Accept invitation
    Route::get('/invitation/{token}', [InvitationController::class, 'acceptForm'])
        ->name('invitations.accept');

    Route::post('/invitation/{token}/accept', [InvitationController::class, 'accept'])
        ->name('invitations.accept.submit');

});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [AuthController::class, 'dashboard'])
        ->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // Short URL Listing
    Route::get('/short-urls', [ShortUrlController::class, 'index'])
        ->middleware('role:Admin,Member')
        ->name('short-urls.index');

    // Short URL Create
    Route::middleware('role:Sales,Manager')->group(function () {
        Route::get('/short-urls/create', [ShortUrlController::class, 'create'])
            ->name('short-urls.create');

        Route::post('/short-urls', [ShortUrlController::class, 'store'])
            ->name('short-urls.store');
    });

    Route::get('/s/{code}', [ShortUrlController::class, 'redirect'])
    ->name('short-urls.redirect');

});

Route::middleware(['auth', 'role:SuperAdmin'])->group(function () {

    Route::resource('companies', CompanyController::class)
        ->only(['index', 'create', 'store']);

});

// Invitation management
Route::middleware('role:SuperAdmin,Admin')->group(function () {

    Route::get('/invitations', [InvitationController::class, 'index'])
        ->name('invitations.index');

    Route::get('/invitations/create', [InvitationController::class, 'create'])
        ->name('invitations.create');

    Route::post('/invitations', [InvitationController::class, 'store'])
        ->name('invitations.store');

});


