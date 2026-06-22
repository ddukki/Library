<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\EditionController;
use App\Http\Controllers\LocationTypeController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\ShelfController;
use Illuminate\Support\Facades\Route;

Route::get('shelves/user', [ShelfController::class, 'user'])->name('shelves.user');
Route::resource('shelves', ShelfController::class);

Route::get('books/search', [BookController::class, 'search'])->name('books.search');
Route::get('books/page/{page}', [BookController::class, 'page'])->name('books.page');
Route::post('books/all', [BookController::class, 'all'])->name('books.all');
Route::resource('books', BookController::class);

Route::get('authors/page/{page}', [AuthorController::class, 'page'])->name('authors.page');
Route::post('authors/all', [AuthorController::class, 'all'])->name('authors.all');
Route::resource('authors', AuthorController::class);

Route::post('editions/{edition}/shelve/{shelf}', [EditionController::class, 'shelve'])->name('editions.shelve');
Route::post('editions/{edition}/unshelve/{shelf}', [EditionController::class, 'unshelve'])->name('editions.unshelve');
Route::resource('editions', EditionController::class);

Route::get('locationtypes/all', [LocationTypeController::class, 'all'])->name('locationtypes.all');
Route::resource('locationtypes', LocationTypeController::class);

Route::resource('quotes', QuoteController::class);

Route::resource('progress', ProgressController::class);
