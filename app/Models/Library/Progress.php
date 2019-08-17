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
            'edition_id',
            'user_id',
            'location_start',
            'location_end',
            'datetime'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function edition() {
        return $this->belongsTo(Edition::class);
    }
}
