<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

class Shelf extends Model
{
    public $fillable = ['name', 'user_id'];

    public function books() {
        return $this->belongsToMany(Book::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
