<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/','App\Http\Controllers\CategoryController@create');
Route::get('/categories/index','App\Http\Controllers\CategoryController@index')->name('categories.index');
Route::get('/categories/create','App\Http\Controllers\CategoryController@create')->name('categories.create');
//Route::get('/categories/create','App\Http\Controllers\CategoryController@create');
//Route::get('/point/create','App\Http\Controllers\CategoryController@createPoint');
Route::post('/categories/store','App\Http\Controllers\CategoryController@store')->name('categories.store');
Route::get('/categories/{id}/edit','App\Http\Controllers\CategoryController@edit')->name('categories.edit');
Route::delete('/categories/destroy/{id}','App\Http\Controllers\CategoryController@destroy')->name('categories.destroy');