<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;
use App\Models\Library\Shelf;
use App\Models\Library\Progress;
use App\Models\Library\Quote;

use App\User;

class Edition extends Model
{
    public $fillable = ['book_id', 'name', 'extent_type_id', 'extent'];

    public function book() {
        return $this->belongsTo(Book::class);
    }

    public function users() {
        return $this->hasManyThrough(User::class, Shelf::class);
    }

    public function shelves() {
        return $this->belongsToMany(Shelf::class, 'edition_shelves');
    }

    public function extent_type() {
        return $this->belongsTo(ExtentType::class);
    }

    public function progress() {
        return $this->hasMany(Progress::class);
    }

    public function quotes() {
        return $this->hasMany(Quote::class);
    }
}
