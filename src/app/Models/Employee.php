<?php

namespace App\Models;

use App\Enums\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'employees';
    protected $guarded = ['id'];
    protected $casts = [
        'position' => Position::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }
}
