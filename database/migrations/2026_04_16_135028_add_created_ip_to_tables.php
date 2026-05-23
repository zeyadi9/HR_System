<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tables = ['overtimes', 'leaves', 'permissions', 'penalties'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->string('created_ip', 45)->nullable()->after('created_at');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['overtimes', 'leaves', 'permissions', 'penalties'];
        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table_bp) {
                $table_bp->dropColumn('created_ip');
            });
        }
    }
};
