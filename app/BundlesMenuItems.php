<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundlesMenuItems extends Model
{
    protected $table = 'bundles_menu_items';
    protected $fillable = ['menu_id', 'next_menu_id', 'description'];


}
