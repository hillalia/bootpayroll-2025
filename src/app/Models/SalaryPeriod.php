<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryPeriod extends Model
{
    protected $table = 'salary_periods';
    protected $guarded = ['id'];

    public function payrolls()
    {
        return $this->hasMany(Payroll::class, 'salary_period_id');
    }
}
