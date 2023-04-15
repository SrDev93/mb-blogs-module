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
use \Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware('auth')->group(function() {
    Route::resource('BlogCategory', 'BlogCategoryController');
    Route::post('BlogCategory-sort', 'BlogCategoryController@sort_item')->name('BlogCategory-sort');

    Route::resource('blogs', 'BlogController');
    Route::resource('tags', 'TagController');
});
