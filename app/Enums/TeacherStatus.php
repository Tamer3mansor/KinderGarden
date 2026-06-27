<?php

namespace App\Enums;

enum TeacherStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';

    public function label(): string
    {
        return match($this) {
            self::Active   => __('teachers.status_active'),
            self::Inactive => __('teachers.status_inactive'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active   => 'success',
            self::Inactive => 'danger',
        };
    }
}