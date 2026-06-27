<?php

namespace App\Enums;

enum StudentGender: string
{
    case Male = 'male';
    case Female = 'female';
    public static function labels(): array
    {
        return [
            self::Male->value => 'Male',
            self::Female->value => 'Female',
        ];
    }
}