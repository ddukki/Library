<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title'];

    public function authors() {
        return $this->belongsToMany(Author::class, 'book_authors');
    }

    public function shelves() {
        return $this->hasMany(Shelf::class);
    }

    public function users() {
        return $this->hasManyThrough(User::class, BookShelf::class);
    }

    public function editions() {
        return $this->hasMany(Edition::class);
    }
}
