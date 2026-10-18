<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class ExtentType extends Model
{
    protected $table = 'extent_types';

    public $fillable = ['name'];

    public function editions()
    {
        return $this->hasMany(Edition::class, 'extent_type_id');
    }
}
