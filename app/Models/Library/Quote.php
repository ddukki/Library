<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Quote extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'edition_id', 'user_id', 'quote', 'location'];

    public function edition() {
        return $this->belongsTo(edition::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
