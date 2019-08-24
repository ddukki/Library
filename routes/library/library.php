<?php
Route::resource('shelves', 'ShelfController');
Route::get('shelves/add', 'ShelfController@add')->name('shelves.add');
