<?php

namespace App\Filament\Resources\FeePlanResource\Pages;

use App\Filament\Resources\FeePlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFeePlan extends EditRecord
{
    protected static string $resource = FeePlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('fee_plans.updated');
    }
}
