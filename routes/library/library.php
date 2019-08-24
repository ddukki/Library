<?php
Route::resource('shelves', 'ShelfController');

Route::get('books/search', 'BookController@search')->name('books.search');
Route::resource('books', 'BookController');
