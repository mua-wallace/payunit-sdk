<?php

use App\Http\Controllers\DonationController;
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

Route::get('/', function () {
    return view('posts.index');
});


// Route::get('/po', function () {
//     return view('welcome');
// });


Route::post('/makePayment', [DonationController::class, 'donate'])->name('makePayment');


Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
