<?php

namespace App\Filament\Resources\FeePlanResource\Pages;

use App\Filament\Resources\FeePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFeePlans extends ListRecords
{
    protected static string $resource = FeePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
