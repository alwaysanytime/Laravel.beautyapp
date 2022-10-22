<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_name', 'description', 'budget', 'start_date', 'end_date', 'client_id', 'status_id', 'createby', 'creation_date',
    ];
}
