<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'bactive', 'company_name', 'company_title', 'logo', 'favicon', 'email', 'tomailaddress', 'timezone_id', 'theme_color', 'recaptcha', 'sitekey', 'secretkey', 'isnotification', 'siteurl', 'zoom_api_key', 'zoom_api_secret',
    ];
}
