<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;
use App\Models\Library\Shelf;
use App\Models\Library\Progress;
use App\Models\Library\Quote;

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
        return $this->belongsToMany(Shelf::class, 'edition_shelves');
    }

    public function location_type() {
        return $this->belongsTo(LocationType::class);
    }

    public function progress() {
        return $this->hasMany(Progress::class);
    }

    public function quotes() {
        return $this->hasMany(Quote::class);
    }
}
