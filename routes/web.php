<?php

use App\Http\Controllers\Listing\ListingController;
use App\Http\Controllers\Listing\MediaController;
use App\Http\Controllers\RegistrationStepTwoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::view('/', 'welcome');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::group(['middleware' => ['registration.not.completed']], function () {
        Route::view('/dashboard', 'dashboard')->name('dashboard');
        Route::resource('listings', ListingController::class);

        Route::group(['prefix' => 'listings/{listing}/media', 'as' => 'listings.media.'], function () {
            Route::get('', [MediaController::class, 'show'])->name('show');
            Route::post('', [MediaController::class, 'store'])->name('store');
            Route::delete('{id}', [MediaController::class, 'destroy'])->name('destroy');
        });
    });

    Route::group([
        'prefix'     => 'registration-step-two',
        'as'         => 'registration-step-two.',
        'middleware' => 'registration.completed',
    ], function () {
        Route::get('', [RegistrationStepTwoController::class, 'edit'])->name('edit');
        Route::patch('', [RegistrationStepTwoController::class, 'update'])->name('update');
    });
});
