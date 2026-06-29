<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->string('type')->default('admin')->after('email');
            $table->string('phone')->nullable()->after('type');
            $table->string('specialization')->nullable()->after('phone');
            $table->date('hire_date')->nullable()->after('specialization');
            $table->decimal('salary', 10, 2)->nullable()->after('hire_date');
            $table->string('status')->nullable()->after('salary');
            $table->date('contract_end_date')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn(['type', 'phone', 'specialization', 'hire_date', 'salary', 'status', 'contract_end_date']);
        });
    }
};
