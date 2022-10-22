<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'task_name', 'description', 'task_group_id', 'task_date', 'bOrder', 'complete_task', 'creation_date', 'project_id'
    ];
}
