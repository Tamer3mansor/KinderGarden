<?php

use App\Models\Admin;
use App\Models\Teacher;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $teacherClass = 'App\Models\Teacher';

        $teachers = DB::table('teachers')->get();
        foreach ($teachers as $teacher) {
            $admin = Admin::where('teacher_id', $teacher->id)->first();

            if ($admin) {
                $admin->update([
                    'type'             => 'teacher',
                    'phone'            => $teacher->phone,
                    'specialization'   => $teacher->specialization,
                    'hire_date'        => $teacher->hire_date,
                    'salary'           => $teacher->salary,
                    'status'           => $teacher->status,
                    'contract_end_date' => $teacher->contract_end_date,
                ]);
            } else {
                $admin = Admin::create([
                    'name'             => $teacher->name,
                    'email'            => $teacher->email,
                    'password'         => bcrypt('password'),
                    'type'             => 'teacher',
                    'phone'            => $teacher->phone,
                    'specialization'   => $teacher->specialization,
                    'hire_date'        => $teacher->hire_date,
                    'salary'           => $teacher->salary,
                    'status'           => $teacher->status,
                    'contract_end_date' => $teacher->contract_end_date,
                ]);
            }
        }

        DB::table('attendances')
            ->where('attendable_type', $teacherClass)
            ->update(['attendable_type' => Admin::class]);

        Admin::whereNull('type')->update(['type' => 'super_admin']);
    }

    public function down(): void
    {
        Admin::where('type', 'teacher')->each(function (Admin $admin) {
            DB::table('teachers')->insert([
                'name'              => $admin->name,
                'phone'             => $admin->phone,
                'email'             => $admin->email,
                'specialization'    => $admin->specialization,
                'hire_date'         => $admin->hire_date,
                'salary'            => $admin->salary,
                'status'            => $admin->status,
                'contract_end_date' => $admin->contract_end_date,
                'created_at'        => $admin->created_at,
                'updated_at'        => $admin->updated_at,
            ]);
        });

        DB::table('attendances')
            ->where('attendable_type', Admin::class)
            ->update(['attendable_type' => 'App\Models\Teacher']);
    }
};
