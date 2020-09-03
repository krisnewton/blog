<?php

Route::group(['prefix' => 'blog', 'as' => 'blog.', 'middleware' => 'web'], function () {
	Route::get('/', 'Apps\BlogController@index')->name('home');

	Route::permanentRedirect('post', '/');
	Route::get('post/{post}', 'Apps\BlogController@post')->name('post');

	Route::permanentRedirect('category', '/');
	Route::get('category/{category}', 'Apps\BlogController@category')->name('category');

	Route::permanentRedirect('label', '/');
	Route::get('label/{label}', 'Apps\BlogController@label')->name('label');

	Route::get('search', 'Apps\BlogController@search')->name('search');
});

Route::group(['middleware' => 'web'], function () {
	Route::get('posts/datatables', 'Apps\PostController@datatables')->name('posts.datatables');
	Route::resource('posts', 'Apps\PostController')->except(['show']);
	Route::resource('categories', 'Apps\CategoryController')->except(['show']);
});
