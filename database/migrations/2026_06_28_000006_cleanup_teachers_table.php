<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        Schema::dropIfExists('teachers');

        Schema::table('admins', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
            $table->dropColumn('teacher_id');
        });

        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->foreignId('teacher_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('specialization');
            $table->date('hire_date');
            $table->decimal('salary', 10, 2);
            $table->string('status');
            $table->date('contract_end_date')->nullable();
            $table->timestamps();
        });
    }
};
