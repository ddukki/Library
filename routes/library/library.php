<?php
Route::resource('shelves', 'ShelfController');

Route::get('books/search', 'BookController@search')->name('books.search');
Route::get('books/page/{page}', 'BookController@page')->name('books.page');
Route::post('books/all', 'BookController@all')->name('books.all');
Route::resource('books', 'BookController');

Route::get('authors/page/{page}', 'AuthorController@page')->name('authors.page');
Route::post('authors/all', 'AuthorController@all')->name('authors.all');
Route::resource('authors', 'AuthorController');

Route::resource('editions', 'EditionController');
Route::resource('locationtypes', 'LocationTypeController');
Route::resource('quotes', 'QuoteController');
Route::resource('progress', 'ProgressController');
