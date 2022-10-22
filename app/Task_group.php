<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task_group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_group_name', 'project_id', 'bOrder'
    ];
}
