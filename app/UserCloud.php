<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCloud extends Model
{
    protected $fillable = ['user_id', 'cloud_disk_id'];
}
