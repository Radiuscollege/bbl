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

Route::get('/', 'UserController@index')->name('home');

Route::get('/amoclient/ready', function () {
    return view('login');
})->name('login');

Route::get('/student', 'StudentController@index')->name('studentlist');

Route::get('/addstudent', 'StudentController@add')->name('addstudent');

Route::post('/api/student', 'StudentController@store')->name('addstudent');

Route::get('/api/student', 'StudentController@showall')->name('getstudents');

Route::get('/student/{id}', 'StudentController@show');

Route::get('/api/student/{id}', 'StudentController@loadstudent');

Route::get('/api/studentmodule', 'StudentModulesController@index');

Route::get('/api/studentmodule/{id}', 'StudentModulesController@getstudent');

Route::post('/api/studentmodule/toggle/{id}', 'StudentModulesController@toggle');

Route::get('/statistics', function () {
    return view('statistics');
})->name('statistics');

Route::get('/module', 'ModuleController@index')->name('modulelist');

Route::get('/api/module', 'ModuleController@showall')->name('getmodules');

Route::get('/module/{id}', 'ModuleController@show')->name('editmodule');

Route::get('/addmodule', 'ModuleController@add')->name('moduleform');

Route::post('/api/module', 'ModuleController@store')->name('addmodule');

Route::get('/api/module/{id}', 'ModuleController@loadmodule')->name('showmodule');

Route::put('/api/module/{id}', 'ModuleController@editmodule')->name('editmodule');

Route::post('/api/cohort', 'CohortController@store')->name('addcohort');

Route::get('/api/cohort', 'CohortController@index')->name('cohort');
