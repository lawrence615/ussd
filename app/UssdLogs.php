<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UssdLogs extends Model
{
    protected $fillable = ['phone', 'text', 'session_id', 'service_code'];
}
