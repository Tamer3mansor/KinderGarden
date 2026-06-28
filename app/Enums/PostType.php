<?php

namespace App\Enums;

enum PostType: string
{
    case Homework  = 'homework';
    case Note      = 'note';
    case AdminNote = 'admin_note';

    public function label(): string
    {
        return match($this) {
            self::Homework  => __('posts.type_homework'),
            self::Note      => __('posts.type_note'),
            self::AdminNote => __('posts.type_admin_note'),
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Homework  => 'info',
            self::Note      => 'success',
            self::AdminNote => 'danger',
        };
    }

    public function icon(): string
    {
        return match($this) {
            self::Homework  => 'heroicon-o-book-open',
            self::Note      => 'heroicon-o-chat-bubble-left',
            self::AdminNote => 'heroicon-o-lock-closed',
        };
    }
}