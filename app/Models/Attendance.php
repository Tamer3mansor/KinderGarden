<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Attendance extends Model
{
    protected $fillable = [
        'attendable_id',
        'attendable_type',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'check_in_time'   => 'datetime',
            'check_out_time'  => 'datetime',
        ];
    }

    public function attendable(): MorphTo
    {
        return $this->morphTo();
    }
}
