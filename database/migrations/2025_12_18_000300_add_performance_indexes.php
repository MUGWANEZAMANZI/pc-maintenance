<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role')) { return; }
            $table->index('role');
            if (Schema::hasColumn('users', 'availability_status')) {
                $table->index('availability_status');
            }
        });

        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                if (Schema::hasColumn('requests', 'date')) {
                    $table->index('date');
                }
                if (Schema::hasColumn('requests', 'status')) {
                    $table->index('status');
                }
            });
        }

        foreach (['pcs','accessories','network_devices'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    if (Schema::hasColumn($tbl, 'health')) {
                        $table->index('health');
                    }
                });
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            try { $table->dropIndex(['role']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['availability_status']); } catch (\Throwable $e) {}
        });

        if (Schema::hasTable('requests')) {
            Schema::table('requests', function (Blueprint $table) {
                try { $table->dropIndex(['date']); } catch (\Throwable $e) {}
                try { $table->dropIndex(['status']); } catch (\Throwable $e) {}
            });
        }

        foreach (['pcs','accessories','network_devices'] as $tbl) {
            if (Schema::hasTable($tbl)) {
                Schema::table($tbl, function (Blueprint $table) use ($tbl) {
                    try { $table->dropIndex(['health']); } catch (\Throwable $e) {}
                });
            }
        }
    }
};
