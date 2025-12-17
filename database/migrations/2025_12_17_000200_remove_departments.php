<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Drop foreign keys and columns referencing departments
        if (Schema::hasTable('computer_labs') && Schema::hasColumn('computer_labs', 'department_id')) {
            Schema::table('computer_labs', function (Blueprint $table) {
                try { $table->dropForeign(['department_id']); } catch (\Throwable $e) {}
                $table->dropColumn('department_id');
            });
        }

        if (Schema::hasTable('requests') && Schema::hasColumn('requests', 'department_id')) {
            Schema::table('requests', function (Blueprint $table) {
                try { $table->dropForeign(['department_id']); } catch (\Throwable $e) {}
                $table->dropColumn('department_id');
            });
        }

        if (Schema::hasTable('departments')) {
            Schema::drop('departments');
        }
    }

    public function down(): void
    {
        // Best-effort restore departments table and columns
        if (!Schema::hasTable('departments')) {
            Schema::create('departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code')->unique();
                $table->text('description')->nullable();
                $table->string('location')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasColumn('computer_labs', 'department_id')) {
            Schema::table('computer_labs', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->after('code')->constrained('departments')->nullOnDelete();
            });
        }

        if (!Schema::hasColumn('requests', 'department_id')) {
            Schema::table('requests', function (Blueprint $table) {
                $table->foreignId('department_id')->nullable()->after('description')->constrained('departments')->nullOnDelete();
            });
        }
    }
};
