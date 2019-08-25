<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    public $fillable = ['first_name', 'middle_name', 'last_name', 'birth_date', 'death_date'];

    public function books() {
        return $this->belongsToMany(Book::class, 'book_authors');
    }
}
