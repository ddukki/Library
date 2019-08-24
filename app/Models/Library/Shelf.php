<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Shelf extends Model
{
    public $fillable = ['name', 'user_id'];

    public function editions() {
        return $this->belongsToMany(Edition::class, 'edition_shelves');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
