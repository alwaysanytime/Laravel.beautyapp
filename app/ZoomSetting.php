<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ZoomSetting extends Model
{
	protected $table = 'zoom_setting';
	
    protected $fillable = [
        'apiurl', 'zoom_api_key', 'zoom_api_secret'
    ];
}
