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

Route::get('/student/search/{value}', 'StudentController@studentsearch');

Route::post('/api/student', 'StudentController@store');

Route::put('/api/student/{id}', 'StudentController@update');

Route::get('/api/student', 'StudentController@showall');

Route::get('/api/student/{id}', 'StudentController@loadstudent');

Route::get('/api/student/search/{value}', 'StudentController@studentsearchresult');

Route::get('/api/studentmodule', 'StudentModulesController@index');

Route::get('/api/studentmodule/{id}', 'StudentModulesController@getstudent');

Route::put('/api/studentmodule/toggle/{id}', 'StudentModulesController@toggle');

Route::put('/api/studentmodule/mark/{id}', 'StudentModulesController@mark');

Route::get('/api/studentmodule/average/{id}', 'StudentModulesController@getaveragemark');

Route::get('/statistics', 'StatisticsController@index');

Route::get('/api/statistics', 'StatisticsController@showstatistics');

Route::get('/module', 'ModuleController@index');

Route::get('/api/module', 'ModuleController@showall');

Route::get('/module/add', 'ModuleController@add');

Route::get('/module/{id}', 'ModuleController@show');

Route::post('/api/module', 'ModuleController@store');

Route::get('/api/module/{id}', 'ModuleController@loadmodule');

Route::put('/api/module/{id}', 'ModuleController@editmodule');

Route::delete('/api/module/{id}', 'ModuleController@destroy');

Route::post('/api/cohort', 'CohortController@store');

Route::get('/api/cohort', 'CohortController@index');
