<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassroomPost extends Model
{
    protected $fillable = [
        'classroom_id', 'admin_id', 'type',
        'date', 'content', 'attachment', 'is_admin_only'
    ];

    protected $casts = [
        'date'          => 'date',
        'is_admin_only' => 'boolean',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
