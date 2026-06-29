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
        Schema::table('classroom_posts', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->constrained()->nullOnDelete()->after('classroom_id');
        });

        $posts = DB::table('classroom_posts')->whereNotNull('teacher_id')->get();
        foreach ($posts as $post) {
            $admin = Admin::where('teacher_id', $post->teacher_id)->first();
            if ($admin) {
                DB::table('classroom_posts')
                    ->where('id', $post->id)
                    ->update(['admin_id' => $admin->id]);
            }
        }

        Schema::table('classroom_posts', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });
    }

    public function down(): void
    {
        Schema::table('classroom_posts', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete()->after('classroom_id');
        });

        $posts = DB::table('classroom_posts')->whereNotNull('admin_id')->get();
        foreach ($posts as $post) {
            $admin = Admin::find($post->admin_id);
            if ($admin && $admin->teacher_id) {
                DB::table('classroom_posts')
                    ->where('id', $post->id)
                    ->update(['teacher_id' => $admin->teacher_id]);
            }
        }

        Schema::table('classroom_posts', function (Blueprint $table) {
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
