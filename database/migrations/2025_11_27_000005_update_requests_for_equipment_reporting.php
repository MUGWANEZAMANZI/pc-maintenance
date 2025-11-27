<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            // Add references to equipment being reported
            $table->foreignId('department_id')->nullable()->after('user_id')->constrained('departments')->nullOnDelete();
            $table->foreignId('computer_lab_id')->nullable()->after('department_id')->constrained('computer_labs')->nullOnDelete();
            $table->foreignId('pc_id')->nullable()->after('computer_lab_id')->constrained('pcs')->nullOnDelete();
            $table->foreignId('accessory_id')->nullable()->after('pc_id')->constrained('accessories')->nullOnDelete();
            $table->foreignId('network_device_id')->nullable()->after('accessory_id')->constrained('network_devices')->nullOnDelete();
            
            // Make old fields nullable since we're using new structure
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('telephone')->nullable()->change();
            $table->string('unit')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['computer_lab_id']);
            $table->dropForeign(['pc_id']);
            $table->dropForeign(['accessory_id']);
            $table->dropForeign(['network_device_id']);
            
            $table->dropColumn(['department_id', 'computer_lab_id', 'pc_id', 'accessory_id', 'network_device_id']);
        });
    }
};
