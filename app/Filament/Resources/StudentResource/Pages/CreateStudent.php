<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use App\Models\FeePlan;
use App\Models\Student;
use App\Models\StudentFee;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;
    protected function handleRecordCreation(array $data) : Model
    {
        $student = Student::create(
            collect($data)->except('fee_plan_id')->toArray()
        );

        $feePlan = FeePlan::find($data['fee_plan_id']);

        if ($feePlan) {
            StudentFee::create([
                'student_id'    => $student->id,
                'fee_plan_id'   => $feePlan->id,
                'academic_year' => now()->year . '-' . (now()->year + 1),
                'total_amount'  => $feePlan->amount * (1 - $feePlan->discount_percentage / 100),
                'status'        => 'unpaid',
                'due_date'      => now()->addMonths($feePlan->duration_months),
            ]);
        }

        return $student;
    }
}
