<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $guarded = [];

    public function salaries() {
        return $this->hasMany(\App\Salary::class);
    }

    public function notifications() {
        return $this->hasMany(\App\Notification::class);
    }
}
