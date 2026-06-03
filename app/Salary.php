<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $guarded = [];

    public function employee(){
        return $this->belongsTo(\App\Employee::class);
    }

    public function payroll(){
        return $this->belongsTo(\App\Payroll::class);
    }
}
