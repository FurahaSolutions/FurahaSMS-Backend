<?php

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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
  return view('welcome', ['users' => App\Models\Setting::first()->id]);
});

Route::name('login')->get('login', function () {
  return response()->json(['error' => 'unauthenticated']);
});
