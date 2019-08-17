<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Edition extends Model
{
    public $fillable = ['book_id', 'name', 'location_type_id', 'location_size'];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function users() {
        return $this->hasManyThrough(User::class, Shelf::class);
    }

    public function shelves() {
        return $this->hasMany(Shelf::class);
    }
}
