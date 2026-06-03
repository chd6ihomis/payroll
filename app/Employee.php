<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    public function payrolls(){
        return $this->hasMany(\App\Payroll::class);
    }

    public function salaries(){
        return $this->hasMany(\App\Salary::class);
    }
}
