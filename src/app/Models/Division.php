<?php

namespace App\Models;

use App\Enums\Position;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $table = 'divisions';
    protected $guarded = ['id'];
}
