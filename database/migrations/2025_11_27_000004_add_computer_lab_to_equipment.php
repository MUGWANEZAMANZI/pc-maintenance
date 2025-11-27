<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            $table->foreignId('computer_lab_id')->nullable()->after('technician_id')->constrained('computer_labs')->nullOnDelete();
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->foreignId('computer_lab_id')->nullable()->after('technician_id')->constrained('computer_labs')->nullOnDelete();
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->foreignId('computer_lab_id')->nullable()->after('technician_id')->constrained('computer_labs')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            $table->dropForeign(['computer_lab_id']);
            $table->dropColumn('computer_lab_id');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropForeign(['computer_lab_id']);
            $table->dropColumn('computer_lab_id');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropForeign(['computer_lab_id']);
            $table->dropColumn('computer_lab_id');
        });
    }
};
