<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment', 'attachment', 'comments_date', 'task_id', 'staff_id', 'project_id', 'battach', 'editable'
    ];
}
