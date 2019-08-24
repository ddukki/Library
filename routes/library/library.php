<?php
Route::get('shelves/add', 'ShelfController@add')->name('shelves.add');
Route::resource('shelves', 'ShelfController');
