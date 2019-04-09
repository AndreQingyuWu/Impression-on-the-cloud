<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $fillable = ['user_id', 'cloud_disk_id', 'photo_id'];

    public function photo(){
        return $this->belongsTo('App\Photo');
    }

    public function cloudDisk(){
        return $this->belongsTo('App\CloudDisk');
    }
}
