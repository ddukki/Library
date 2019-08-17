<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'edition', 'location_type', 'location_size'];

    public function authors() {
        return $this->hasMany(Author::class);
    }

    public function shelves() {
        return $this->hasMany(Shelf::class);
    }

    public function users() {
        return $this->hasManyThrough(User::class, BookShelf::class);
    }
}
