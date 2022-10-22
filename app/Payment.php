<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'invoice_no', 'title', 'deadline', 'amount', 'project_id', 'payment_status_id', 'payment_method',
    ];
}
