<?php

namespace App\Enums;

enum AdminType: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Teacher = 'teacher';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => __('admins.type_super_admin'),
            self::Admin      => __('admins.type_admin'),
            self::Teacher    => __('admins.type_teacher'),
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SuperAdmin => 'danger',
            self::Admin      => 'warning',
            self::Teacher    => 'success',
        };
    }
}
