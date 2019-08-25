<?php
Route::resource('shelves', 'ShelfController');

Route::get('books/search', 'BookController@search')->name('books.search');
Route::get('books/all', 'BookController@all')->name('books.all');
Route::resource('books', 'BookController');

Route::resource('authors', 'AuthorController');
