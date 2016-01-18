<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundlesMenu extends Model
{
    protected $table = 'bundles_menu';
    protected $fillable = ['id', 'title', 'menu_type', 'is_root'];
}
