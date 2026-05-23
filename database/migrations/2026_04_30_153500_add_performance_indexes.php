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
        // Already done: overtimes, leaves, permissions, check_in_outs

        Schema::table('penalties', function (Blueprint $table) {
            $table->index('status');
        });

        Schema::table('incentives', function (Blueprint $table) {
            $table->index('date');
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->index('date');
            $table->index('status');
        });

        Schema::table('admin_notes', function (Blueprint $table) {
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Also need to drop the ones that were partially created if we rollback
        foreach (['overtimes', 'leaves', 'permissions', 'check_in_outs'] as $t) {
            try {
                Schema::table($t, function (Blueprint $table) use ($t) {
                    if ($t != 'incentives' && $t != 'admin_notes') {
                        $table->dropIndex(['date']);
                        $table->dropIndex(['status']);
                    } else {
                        $table->dropIndex(['date']);
                    }
                });
            } catch (\Exception $e) {}
        }

        Schema::table('penalties', function (Blueprint $table) {
            $table->dropIndex(['status']);
        });

        Schema::table('incentives', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });

        Schema::table('settlements', function (Blueprint $table) {
            $table->dropIndex(['date']);
            $table->dropIndex(['status']);
        });

        Schema::table('admin_notes', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });
    }
};
