<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Shelf extends Model
{
    public $fillable = ['name', 'user_id'];

    public function editions() {
        return $this->hasMany(Edition::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
