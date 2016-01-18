<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BundlesSubscription extends Model
{
    protected $fillable = ['id', 'user_id', 'bundles_plan'];
}
