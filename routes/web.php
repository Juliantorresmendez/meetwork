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


Route::resource('note', 'NoteController');
Route::get('/formatPlaces', ['as' => 'place.format', 'uses' => 'HomeController@formatPlaces']);
Route::get('/sites', ['as' => 'sites.search', 'uses' => 'SiteController@searchSites']);
Route::get('/formatDirections', ['as' => 'sites.format', 'uses' => 'HomeController@formatDirections']);
Route::get('/getFilters', ['as' => 'sites.getFilters', 'uses' => 'SiteController@getFilters']);
Route::get('/siteSystem/{id}', ['as' => 'sites.individual', 'uses' => 'SiteController@getIndividual']);
Route::post('/review/{id}', ['as' => 'sites.postReview', 'uses' => 'SiteController@postReview']);
Route::get('/getReviewsCount/{id}', ['as' => 'sites.getReviewsCount', 'uses' => 'ReviewController@getReviewsCount']);

 Route::get('/app/profile/{id}', ['as' => 'user.profile', 'uses' => 'UserController@profile']);
 Route::get('/app/profile/', ['as' => 'user.profile', 'uses' => 'UserController@profileIndividual']);
 Route::get('/demoImage/', ['as' => 'user.profile', 'uses' => 'HomeController@demoImage']);

Route::post('/app/LoginUser', ['as' => 'account.LoginUser', 'uses' => 'HomeController@LoginUser']);
Route::post('/reserv/{id}', ['as' => 'sites.reserv', 'uses' => 'SiteController@postReserv']);


Route::post('/registerAccount', ['as' => 'account.registerAccount', 'uses' => 'Auth\RegisterController@registerAccount']);
Route::get('/register/verify/{code}/{email}', ['as' => 'user.verify', 'uses' => 'Auth\RegisterController@verifyAccount']);

Route::get('/resendCode', ['as' => 'sites.resendCode', 'uses' => 'Auth\RegisterController@resendCode']);
Route::get('/logoutPage', ['as' => 'home.logout', 'uses' => 'HomeController@logout']);

Route::get('/{vue_capture?}', function () {
   return view('layouts.master');
 })->where('vue_capture', '[\/\w\.-]*');
Route::get('/site/{vue_capture?}/{strig}', function () {
   return view('layouts.master');
 })->where('vue_capture', '[\/\w\.-]*');

