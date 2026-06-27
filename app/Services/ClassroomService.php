<?php

namespace App\Services;

use App\Models\Classroom;

class ClassroomService
{
    public function transferStudents(Classroom $source, int $targetId): array
    {
        $studentsCount   = $source->students()->count();
        $targetClassroom = Classroom::find($targetId);
        $availableSpace  = $targetClassroom->max_capacity - $targetClassroom->students()->count();
// dd($availableSpace , $studentsCount , $targetClassroom->max_capacity , $targetClassroom->students()->count());
        if ($studentsCount === 0) {
            return ['success' => false, 'message' => 'no_students'];
        }

        if ($availableSpace < $studentsCount) {
            return [
                'success'   => false,
                'message'   => 'insufficient_capacity',
                'available' => $availableSpace,
                'required'  => $studentsCount,
            ];
        }

        $source->students()->update(['classroom_id' => $targetId]);

        return ['success' => true, 'count' => $studentsCount];
    }
}