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
    protected $fillable = ['title', 'edition', 'location_type', 'location_size'];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
