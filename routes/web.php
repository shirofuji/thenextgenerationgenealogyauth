<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth;

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

Route::get('/', array(Auth::class, 'index'));
Route::get('/password', array(Auth::class, 'password'));
Route::post('/signup', array(Auth::class, 'signup'));
Route::post('/login', array(Auth::class, 'login'));
Route::post('/authfb', array(Auth::class, 'authgoogle'));
Route::post('/authgoogle', array(Auth::class, 'authgoogle'));

Route::post('/submit_reset', array(Auth::class, 'submit_reset'));
Route::post('/create_password', array(Auth::class, 'create_password'));
Route::any('/verify_login', array(Auth::class, 'verify_login'));

Route::get('/activate', array(Auth::class, 'activate_account'));