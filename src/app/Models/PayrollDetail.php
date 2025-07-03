<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayrollDetail extends Model
{
    protected $table = 'payroll_details';
    protected $guarded = ['id'];

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }
}
