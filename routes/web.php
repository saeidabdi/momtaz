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

Route::get('/', 'AdminController@login');
Route::post('/login', 'AdminController@login_admin');
Route::get('/dashbord', 'AdminController@dashbord');
Route::get('/getuser', 'AdminController@getuser');
Route::get('/slider', 'AdminController@slider');
Route::get('/exit_admin', 'AdminController@exit_admin');
Route::post('/formSubmit', 'AdminController@formSubmit');
Route::post('/formimgplan', 'AdminController@formimgplan');
Route::get('/get_imag_slide', 'AdminController@get_imag_slide');
Route::post('slider_img', 'AdminController@slider_img');
Route::get('/stu', 'AdminController@stu');
Route::get('/get_stu', 'AdminController@get_stu');
Route::post('/search_stu', 'AdminController@search_stu');
Route::get('/lesson', 'AdminController@lesson');
Route::post('/add_lesson', 'AdminController@add_lesson');
Route::post('/get_lesson', 'AdminController@get_lesson');
Route::get('/mosh', 'AdminController@mosh');
Route::get('/get_mosh', 'AdminController@get_mosh');
Route::post('/search_mosh', 'AdminController@search_mosh');
Route::post('/unactive_mosh', 'AdminController@unactive_mosh');
Route::get('/plan', 'AdminController@plan');
Route::get('/get_message', 'AdminController@get_message');
Route::post('/edit_message', 'AdminController@edit_message');
Route::post('/add_plan', 'AdminController@add_plan');
Route::get('/get_plan', 'AdminController@get_plan');
Route::post('/delete_plan', 'AdminController@delete_plan');

Route::group(['prefix' => 'api'], function () {
    Route::post('/mobile', 'ApiController@mobile');
    Route::post('/ok_code', 'ApiController@ok_code');
    Route::post('/register', 'ApiController@register');
    Route::post('/get_home', 'ApiController@get_home');
    Route::post('/edu_plan', 'ApiController@edu_plan');
    Route::post('/send_edu', 'ApiController@send_edu');
    Route::post('/get_edu', 'ApiController@get_edu');
    Route::get('/test', 'ApiController@test');
});
