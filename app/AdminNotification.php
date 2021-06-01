<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminNotification extends Model
{
    protected $fillable = ['type','notifiable_type','notifiable_id','data'];
}
