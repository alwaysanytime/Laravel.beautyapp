<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lankeyvalue extends Model
{
    protected $fillable = [
        'language_code', 'language_key', 'language_value',
    ];
}
