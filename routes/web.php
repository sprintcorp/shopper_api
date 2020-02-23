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

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
if ($options['register'] ?? true) {
    Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\RegisterController@register');
}

// Password Reset Routes...
//if ($options['reset'] ?? true) {
//    $this->resetPassword();
//}
//
//// Password Confirmation Routes...
//if ($options['confirm'] ??
//    class_exists($this->prependGroupNamespace('Auth\ConfirmPasswordController'))) {
//    $this->confirmPassword();
//}

// Email Verification Routes...
if ($options['verify'] ?? false) {
    $this->emailVerification();
}
//Auth::routes();
Route::get('home/my-tokens','HomeController@getTokens')->name('personal-tokens');
Route::get('home/my-clients','HomeController@getClients')->name('personal-clients');
Route::get('home/authorized-clients','HomeController@getAuthorizedClients')->name('authorized-clients');
Route::get('/home', 'HomeController@index')->name('home');
