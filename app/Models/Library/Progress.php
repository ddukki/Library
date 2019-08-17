<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class Progress extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
            'book_id',
            'user_id',
            'book_location_start',
            'book_location_end',
            'datetime'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function book() {
        return $this->belongsTo(Book::class);
    }
}
