<?php

namespace App\Filament\Resources\FeePlanResource\Pages;

use App\Filament\Resources\FeePlanResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFeePlan extends CreateRecord
{
    protected static string $resource = FeePlanResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return __('fee_plans.created');
    }
}
