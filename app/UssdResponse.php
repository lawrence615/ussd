<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UssdResponse extends Model
{
    protected $fillable = ['id', 'phone', 'menu_id', 'menu_item_id', 'response'];
}
