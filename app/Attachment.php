<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attach_title', 'attachment', 'attach_date', 'comments_id', 'task_id', 'staff_id', 'project_id'
    ];
}
