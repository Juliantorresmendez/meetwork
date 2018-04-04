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

 Route::group(['middleware' => 'fw-block-blacklisted'], function () 
{
Route::get('/', ['as' => 'home.indes', 'uses' => 'HomeController@index']);
Route::get('UploadDemo', ['as' => 'home.UploadDemo', 'uses' => 'HomeController@UploadDemo']);

   
Route::resource('note', 'NoteController');


Route::get('/searchAdmin', ['as' => 'user.searchAdmin', 'uses' => 'HomeController@searchAdmin']);
Route::get('/createRolAdmin/{id}', ['as' => 'user.createRolAdmin', 'uses' => 'HomeController@createRolAdmin']);
Route::get('/sendMail', ['as' => 'user.sendMail', 'uses' => 'HomeController@sendMail']);
Route::get('/login', ['as' => 'user.sendMail', 'uses' => 'HomeController@login']);
Route::get('/sendWelcome/{id}', ['as' => 'place.sendWelcome', 'uses' => 'HomeController@sendWelcome']);

Route::get('/formatPlaces', ['as' => 'place.format', 'uses' => 'HomeController@formatPlaces']);
Route::get('/sites', ['as' => 'sites.search', 'uses' => 'SiteController@searchSites']);
Route::get('/formatDirections', ['as' => 'sites.format', 'uses' => 'HomeController@formatDirections']);
Route::get('/getFilters', ['as' => 'sites.getFilters', 'uses' => 'SiteController@getFilters']); 
Route::get('/siteSystem/{id}', ['as' => 'sites.individual', 'uses' => 'SiteController@getIndividual']);
Route::post('/review/{id}', ['as' => 'sites.postReview', 'uses' => 'ReviewController@postReview']);  
Route::get('/getReviewsCount/{id}', ['as' => 'sites.getReviewsCount', 'uses' => 'ReviewController@getReviewsCount']);
Route::get('/getAdmiSites', ['as' => 'sites.getAdmiSites', 'uses' => 'HomeController@getAdmiSites']);
Route::get('/getUserSites', ['as' => 'sites.getUserSites', 'uses' => 'HomeController@getUserSites']);
Route::post('/editAdminSite', ['as' => 'user.editAdminSite', 'uses' => 'SiteController@editAdminSite']);
Route::delete('/deleteAdminImage/{site}/{image}', ['as' => 'user.deleteAdminImage', 'uses' => 'SiteController@deleteAdminImage']);
Route::delete('/deleteLogoAdmin/{site}/{image}', ['as' => 'user.deleteLogoAdmin', 'uses' => 'SiteController@deleteLogoAdmin']);
Route::any('/tokenfacebook', ['as' => 'sites.tokenfacebook', 'uses' => 'Test\TestController@tokenfacebook']);
Route::any('/showLog', ['as' => 'sites.showLog', 'uses' => 'Test\TestController@showLog']);

 Route::get('/app/profile/{id}', ['as' => 'user.profile', 'uses' => 'UserController@profile']);
 Route::get('/app/profile/', ['as' => 'user.profile', 'uses' => 'UserController@profileIndividual']);
 Route::get('/demoImage/', ['as' => 'user.profile', 'uses' => 'HomeController@demoImage']);

Route::post('/app/LoginUser', ['as' => 'account.LoginUser', 'uses' => 'HomeController@LoginUser']);
Route::post('/reserv/{id}', ['as' => 'sites.reserv', 'uses' => 'SiteController@postReserv']);
Route::get('/processLeads', ['as' => 'user.processLeads', 'uses' => 'HomeController@processLeads']);

Route::post('/resetPasswordByEmail', ['as' => 'account.resetPasswordByEmail', 'uses' => 'HomeController@resetPasswordByEmail']);
Route::post('/linksocialData', ['as' => 'account.linksocialData', 'uses' => 'HomeController@linksocialData']);

Route::get('/verifyEmail', ['as' => 'user.verifyEmail', 'uses' => 'HomeController@verifyEmail']);


Route::post('/registerAccount', ['as' => 'account.registerAccount', 'uses' => 'Auth\RegisterController@registerAccount']);
Route::get('/register/verify/{code}/{email}', ['as' => 'user.verify', 'uses' => 'Auth\RegisterController@verifyAccount']);

Route::get('/resendCode', ['as' => 'sites.resendCode', 'uses' => 'Auth\RegisterController@resendCode']);
Route::get('/logout', ['as' => 'home.logout', 'uses' => 'HomeController@logout']);
Route::post('/auth/facebook', ['as' => 'auth.facebook', 'uses' => 'HomeController@validateFacebook']);
Route::post('/editProfile', ['as' => 'user.edit', 'uses' => 'UserController@EditProfiile']);
Route::post('/editAvatar', ['as' => 'user.editAvatar', 'uses' => 'UserController@editAvatar']);
Route::post('/uploadPhotoSite', ['as' => 'user.uploadPhoto', 'uses' => 'SiteController@uploadPhotoSite']);
Route::post('/uploadLogoSite', ['as' => 'user.uploadLogoSite', 'uses' => 'SiteController@uploadLogoSite']);
Route::post('/publishSite', ['as' => 'user.publish', 'uses' => 'SiteController@publish']);
Route::get('/setowner', ['as' => 'home.setowner', 'uses' => 'HomeController@setowner']);
Route::post('/editSite', ['as' => 'user.edit', 'uses' => 'SiteController@editSite']);
Route::delete('/deleteImage/{site}/{image}', ['as' => 'user.deleteImage', 'uses' => 'SiteController@deleteImage']);

Route::get('/reservations/{id}/{name}', ['as' => 'home.reservations', 'uses' => 'SiteController@reservations']);



Route::post('/getReservations/{id}', ['as' => 'user.getReservations', 'uses' => 'SiteController@getReservations']);
Route::post('/declineReserv/{id}', ['as' => 'user.declineReserv', 'uses' => 'SiteController@declineReserv']);
Route::post('/acceptReserv/{id}', ['as' => 'user.acceptReserv', 'uses' => 'SiteController@acceptReserv']);


Route::get('/editadmin/{id}', ['as' => 'sites.editadmin', 'uses' => 'SiteController@editadmin']);
Route::get('/adminMeetwork', ['as' => 'sites.admin', 'uses' => 'SiteController@admin']);

Route::post('/submitRecomendation', ['as' => 'user.submitRecomendation', 'uses' => 'HomeController@submitRecomendation']);
Route::get('/resetPassword/', ['as' => 'user.reset', 'uses' => 'Auth\ResetPasswordController@reset']);
Route::post('/changePasswordUser', ['as' => 'user.changePasswordUser', 'uses' => 'HomeController@changePasswordUser']);

Route::get('/sendEmail/{id}', ['as' => 'user.reset', 'uses' => 'HomeController@sendEmail']);



Route::get('/{vue_capture?}', function () {
   return view('layouts.master');
 })->where('vue_capture', '[\/\w\.-]*');
Route::get('/site/{vue_capture?}/{strig}', function () {
   return view('layouts.master');
 })->where('vue_capture', '[\/\w\.-]*');

  });