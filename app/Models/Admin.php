<?php

namespace App\Models;

use Database\Factories\AdminFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'type', 'phone', 'specialization', 'hire_date', 'salary', 'status', 'contract_end_date'])]
#[Hidden(['password', 'remember_token'])]
class Admin extends Authenticatable implements FilamentUser
{
    use HasRoles;
    /** @use HasFactory<AdminFactory> */
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'password'          => 'hashed',
            'hire_date'         => 'date',
            'contract_end_date' => 'date',
            'salary'            => 'decimal:2',
        ];
    }

    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class, 'classroom_admin')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function attendances(): MorphMany
    {
        return $this->morphMany(Attendance::class, 'attendable');
    }

    public function classroomPosts(): HasMany
    {
        return $this->hasMany(ClassroomPost::class, 'admin_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
