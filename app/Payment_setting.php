<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment_setting extends Model
{
    protected $fillable = [
        'publickey', 'secretkey', 'payment_method', 'isenable',
    ];
}
