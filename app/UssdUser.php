<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UssdUser extends Model
{
    protected $table = 'ussd_users';

    protected $fillable = ['id','phone', 'session', 'progress', 'menu_id', 'menu_item_id'];
}
