<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('index');
});

Route::get('/login',function (){
    return view('login');
})->middleware('IsLog')->name('login');

Route::get('/signup',function (){
    return view('signup');
})->middleware('IsLog')->name('signup');

Route::post('/signup',[UserController::class,'store'])->middleware('IsLog')->name('store-user');
