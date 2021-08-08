<?php

use Illuminate\Support\Facades\Route;

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
Route::get('search', 'SiteWideSearchController@search');
Route::get('search-algolia', 'SiteWideSearchController@searchAlgolia');
Route::get('search-algolia-ui', 'SiteWideSearchController@searchAlgoliaUI');
Route::get('search-algolia-multiple-model', 'SiteWideSearchController@searchAlgoliaMultipleModel');
Route::get('/', function () {
    return view('welcome');
});
