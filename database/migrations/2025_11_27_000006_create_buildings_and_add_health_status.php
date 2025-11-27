<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Add building_id and health to computer_labs
        Schema::table('computer_labs', function (Blueprint $table) {
            $table->foreignId('building_id')->nullable()->after('department_id')->constrained('buildings')->nullOnDelete();
        });

        // Add health status and device_name to equipment tables
        Schema::table('pcs', function (Blueprint $table) {
            $table->foreignId('building_id')->nullable()->after('computer_lab_id')->constrained('buildings')->nullOnDelete();
            $table->string('device_name')->nullable()->after('brand');
            $table->enum('health', ['healthy', 'malfunctioning', 'dead'])->default('healthy')->after('registration_year');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->foreignId('building_id')->nullable()->after('computer_lab_id')->constrained('buildings')->nullOnDelete();
            $table->string('device_name')->nullable()->after('brand');
            $table->enum('health', ['healthy', 'malfunctioning', 'dead'])->default('healthy')->after('registration_year');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->foreignId('building_id')->nullable()->after('computer_lab_id')->constrained('buildings')->nullOnDelete();
            $table->string('device_name')->nullable()->after('brand');
            $table->enum('health', ['healthy', 'malfunctioning', 'dead'])->default('healthy')->after('registration_year');
        });
    }

    public function down(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropColumn(['building_id', 'device_name', 'health']);
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropColumn(['building_id', 'device_name', 'health']);
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropColumn(['building_id', 'device_name', 'health']);
        });

        Schema::table('computer_labs', function (Blueprint $table) {
            $table->dropForeign(['building_id']);
            $table->dropColumn('building_id');
        });

        Schema::dropIfExists('buildings');
    }
};
