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

Route::get('/', 'UserController@index', function () {
    return view('teacher');
})->name('home');

Route::get('/amoclient/ready', function () {
    return view('login');
})->name('login');

Route::get('/student', function () {
    return view('studentlist');
})->name('studentlist');

Route::get('/student/{id}', function () {
    return view('student');
})->name('student');

Route::get('/statistics', function () {
    return view('statistics');
})->name('statistics');

Route::get('/module', 'ModuleController@index', function () {
    return view('modulelist');
})->name('modulelist');

Route::get('/module/{id}', 'ModuleController@show', function () {
    return view('module');
})->name('module');
