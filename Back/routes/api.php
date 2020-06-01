<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/user/login', 'UserController@login');
Route::post('/user/logout', 'UserController@logout');
Route::post('/user/registration', 'UserController@registration');

Route::put('/user', 'UserController@update');

Route::get('/user', 'UserController@geUserByToken');

Route::post('/user/loadPhoto', 'UserController@loadPhoto');

Route::get('/project', 'ProjectController@get');
Route::post('/project', 'ProjectController@create');
Route::put('/project', 'ProjectController@update');
Route::delete('/project', 'ProjectController@delete');

Route::get('/project/{id}', 'ProjectController@getById');

Route::post('/projectImport', 'ProjectController@import');

Route::post('/skill', 'SkillController@create');
Route::put('/skill', 'SkillController@update');
Route::delete('/skill', 'SkillController@delete');

Route::post('/projectHasSkill', 'ProjectHasSkillController@create');
Route::delete('/projectHasSkill', 'ProjectHasSkillController@delete');

Route::post('/projectHasAttachment', 'ProjectHasAttachmentController@create');
Route::delete('/projectHasAttachment', 'ProjectHasAttachmentController@delete');
