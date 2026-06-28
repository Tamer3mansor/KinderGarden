<?php

namespace App\Filament\Resources\ClassroomPostResource\Pages;

use App\Filament\Resources\ClassroomPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassroomPosts extends ListRecords
{
    protected static string $resource = ClassroomPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
