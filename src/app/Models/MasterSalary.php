<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterSalary extends Model
{
    protected $table = 'master_salaries';
    protected $guarded = ['id'];

    public function division()
    {
        return $this->belongsTo(\App\Models\Division::class);
    }
}
