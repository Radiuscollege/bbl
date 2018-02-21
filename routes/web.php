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

Route::get('/', 'UserController@index');

Route::get('/amoclient/ready', function () {
    return view('login');
})->name('login');

Route::get('/student', 'StudentController@index');

Route::get('/student/add', 'StudentController@add');

Route::get('/student/{id}', 'StudentController@show');

Route::get('/student/search/{value}', 'StudentController@studentSearch');

Route::post('/api/student', 'StudentController@store');

Route::put('/api/student/{id}', 'StudentController@update');

Route::get('/api/student', 'StudentController@showAll');

Route::get('/api/student/own', 'StudentController@showOwn');

Route::get('/api/student/{id}', 'StudentController@loadStudent');

Route::delete('/api/student/{id}', 'StudentController@destroy');

Route::get('/api/student/{value}/search', 'StudentController@studentSearchResult');

Route::get('/api/student/studentmodule', 'StudentController@getStudentModules');

Route::get('/api/student/{id}/studentmodule', 'StudentModulesController@getStudent');

Route::put('/api/studentmodule/{id}/toggle', 'StudentModulesController@toggle');

Route::put('/api/studentmodule/{id}/mark', 'StudentModulesController@mark');

Route::get('/statistics', 'StatisticsController@index');

Route::get('/api/statistics', 'StatisticsController@showStatistics');

Route::get('/api/statistics/average', 'StatisticsController@getOwnMarks');

Route::get('/api/statistics/{id}/average', 'StatisticsController@getAverageMarks');

Route::get('/module', 'ModuleController@index');

Route::get('/api/module', 'ModuleController@showAll');

Route::get('/module/add', 'ModuleController@add');

Route::get('/module/{id}', 'ModuleController@show');

Route::post('/api/module', 'ModuleController@store');

Route::get('/api/module/{id}', 'ModuleController@loadModule');

Route::put('/api/module/{id}', 'ModuleController@update');

Route::delete('/api/module/{id}', 'ModuleController@destroy');

Route::post('/api/cohort', 'CohortController@store');

Route::get('/api/cohort', 'CohortController@index');
