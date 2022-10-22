<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task_staff_map extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'staff_id', 'task_id', 'project_id'
    ];
}
