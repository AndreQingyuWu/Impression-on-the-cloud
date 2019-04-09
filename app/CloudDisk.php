<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CloudDisk extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_clouds');
    }
}
