<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            if (!Schema::hasColumn('pcs', 'specifications')) {
                $table->text('specifications')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pcs', function (Blueprint $table) {
            if (Schema::hasColumn('pcs', 'specifications')) {
                $table->dropColumn('specifications');
            }
        });
    }
};