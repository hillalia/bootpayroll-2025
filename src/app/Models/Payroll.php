<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $table = 'payrolls';
    protected $guarded = ['id'];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function period()
    {
        return $this->belongsTo(SalaryPeriod::class, 'salary_period_id');
    }

    public function details()
    {
        return $this->hasMany(PayrollDetail::class);
    }
}
