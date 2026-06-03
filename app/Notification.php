<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $guarded = [];

    public function payroll(){
        return $this->belongsTo(\App\Payroll::class);
    }

    public function user(){
        return $this->belongsTo(\App\User::class);
    }
}
