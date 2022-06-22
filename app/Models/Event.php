<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    public function workshops() {
        return $this->hasMany(Workshop::class);
    }

    public function workshopsInitial() {
        return $this->hasOne(Workshop::class)->orderBy('id','ASC');
    }
}
