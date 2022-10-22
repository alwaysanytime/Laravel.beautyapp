<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailtext extends Model
{
	
	protected $table = 'mailtext';
	
    protected $fillable = [
        'subject_key', 'subject_value', 'body_key', 'body_value'
    ];
}
