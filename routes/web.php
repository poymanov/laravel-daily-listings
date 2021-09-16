<?php

use App\Http\Controllers\Listing\CategoryController;
use App\Http\Controllers\Listing\ColorController;
use App\Http\Controllers\Listing\ListingController;
use App\Http\Controllers\Listing\MediaController;
use App\Http\Controllers\Listing\MessageController;
use App\Http\Controllers\Listing\SizeController;
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

        Route::group(['prefix' => 'listings/{listing}/categories', 'as' => 'listings.categories.'], function () {
            Route::get('', [CategoryController::class, 'edit'])->name('edit');
            Route::post('', [CategoryController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'listings/{listing}/colors', 'as' => 'listings.colors.'], function () {
            Route::get('', [ColorController::class, 'edit'])->name('edit');
            Route::post('', [ColorController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'listings/{listing}/sizes', 'as' => 'listings.sizes.'], function () {
            Route::get('', [SizeController::class, 'edit'])->name('edit');
            Route::post('', [SizeController::class, 'update'])->name('update');
        });

        Route::group(['prefix' => 'listings/{listing}/messages', 'as' => 'listings.messages.'], function () {
            Route::get('create', [MessageController::class, 'create'])->name('create');
            Route::post('', [MessageController::class, 'store'])->middleware('throttle:1,1')->name('store');
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
