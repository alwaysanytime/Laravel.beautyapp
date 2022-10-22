<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project_staff_map extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'staff_id', 'bActive'
    ];
}
