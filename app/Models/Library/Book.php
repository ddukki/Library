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
    protected $fillable = ['title'];

    public function authors() {
        return $this->belongsToMany(Author::class, 'book_authors');
    }

    public function editions() {
        return $this->hasMany(Edition::class);
    }
}
