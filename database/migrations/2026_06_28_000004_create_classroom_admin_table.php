<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_admin', function (Blueprint $table) {
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->primary(['classroom_id', 'admin_id']);
        });

        $rows = DB::table('classroom_teacher')->get();
        foreach ($rows as $row) {
            $admin = Admin::where('teacher_id', $row->teacher_id)->first();
            if ($admin) {
                DB::table('classroom_admin')->insert([
                    'classroom_id' => $row->classroom_id,
                    'admin_id'     => $admin->id,
                    'is_primary'   => $row->is_primary,
                    'created_at'   => $row->created_at,
                    'updated_at'   => $row->updated_at,
                ]);
            }
        }

        Schema::dropIfExists('classroom_teacher');
    }

    public function down(): void
    {
        Schema::create('classroom_teacher', function (Blueprint $table) {
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            $table->primary(['classroom_id', 'teacher_id']);
        });

        $rows = DB::table('classroom_admin')->get();
        foreach ($rows as $row) {
            $admin = Admin::find($row->admin_id);
            if ($admin && $admin->type === 'teacher') {
                $teacher = DB::table('teachers')->where('id', $admin->teacher_id)->first();
                if ($teacher) {
                    DB::table('classroom_teacher')->insert([
                        'classroom_id' => $row->classroom_id,
                        'teacher_id'   => $teacher->id,
                        'is_primary'   => $row->is_primary,
                        'created_at'   => $row->created_at,
                        'updated_at'   => $row->updated_at,
                    ]);
                }
            }
        }

        Schema::dropIfExists('classroom_admin');
    }
};
