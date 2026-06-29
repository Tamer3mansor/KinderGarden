<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'level_stage', 'max_capacity'])]
class Classroom extends Model
{
    /** @use HasFactory<\Database\Factories\ClassroomFactory> */
    use HasFactory;

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(Admin::class, 'classroom_admin')
            ->withPivot('is_primary')
            ->withTimestamps();
    }
}
