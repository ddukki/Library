<?php

namespace App\Models\Library;

use Illuminate\Database\Eloquent\Model;

use App\User;

class ExtentType extends Model
{
    protected $table = 'extent_types';

    public $fillable = ['name'];
}
