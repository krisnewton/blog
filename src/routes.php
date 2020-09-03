<?php

Route::group(['prefix' => 'blog', 'as' => 'blog.', 'middleware' => 'web'], function () {
	Route::get('/', 'App\Http\Controllers\Apps\BlogController@index')->name('home');

	Route::permanentRedirect('post', '/');
	Route::get('post/{post}', 'App\Http\Controllers\Apps\BlogController@post')->name('post');

	Route::permanentRedirect('category', '/');
	Route::get('category/{category}', 'App\Http\Controllers\Apps\BlogController@category')->name('category');

	Route::permanentRedirect('label', '/');
	Route::get('label/{label}', 'App\Http\Controllers\Apps\BlogController@label')->name('label');

	Route::get('search', 'App\Http\Controllers\Apps\BlogController@search')->name('search');
});

Route::group(['middleware' => 'web'], function () {
	Route::get('posts/datatables', 'App\Http\Controllers\Apps\PostController@datatables')->name('posts.datatables');
	Route::resource('posts', 'App\Http\Controllers\Apps\PostController')->except(['show']);
	Route::resource('categories', 'App\Http\Controllers\Apps\CategoryController')->except(['show']);
});
