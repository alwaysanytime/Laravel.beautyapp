<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Meeting_invitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id', 'meeting_id'
    ];
}
