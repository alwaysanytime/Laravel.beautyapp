<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vpcode extends Model
{
    protected $fillable = [
        'bactive', 'resetkey',
    ];
}
