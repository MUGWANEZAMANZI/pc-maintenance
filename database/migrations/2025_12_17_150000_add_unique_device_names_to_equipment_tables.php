<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            if (!Schema::hasColumn('pcs', 'device_name')) {
                $table->string('device_name')->nullable();
            }
            $table->unique('device_name', 'pcs_device_name_unique');
        });

        Schema::table('accessories', function (Blueprint $table) {
            if (!Schema::hasColumn('accessories', 'device_name')) {
                $table->string('device_name')->nullable();
            }
            $table->unique('device_name', 'accessories_device_name_unique');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            if (!Schema::hasColumn('network_devices', 'device_name')) {
                $table->string('device_name')->nullable();
            }
            $table->unique('device_name', 'network_devices_device_name_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            $table->dropUnique('pcs_device_name_unique');
        });

        Schema::table('accessories', function (Blueprint $table) {
            $table->dropUnique('accessories_device_name_unique');
        });

        Schema::table('network_devices', function (Blueprint $table) {
            $table->dropUnique('network_devices_device_name_unique');
        });
    }
};
