<?php

namespace App\Jobs;

use App\Models\Admin;
use App\Models\Teacher;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckContractExpiryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiringTeachers = Teacher::query()
            ->whereNotNull('contract_end_date')
            ->whereBetween('contract_end_date', [
                now(),
                now()->addMonths(2),
            ])
            ->get();

        if ($expiringTeachers->isEmpty()) {
            return;
        }

        $admins = Admin::all();

        foreach ($expiringTeachers as $teacher) {
            foreach ($admins as $admin) {
                Notification::make()
                    ->title('عقد قارب على الانتهاء')
                    ->body("عقد {$teacher->name} ينتهي في {$teacher->contract_end_date->format('Y-m-d')}")
                    ->color('warning')
                    ->icon('heroicon-o-clock')
                    ->sendToDatabase($admin);
            }
        }
    }
}
