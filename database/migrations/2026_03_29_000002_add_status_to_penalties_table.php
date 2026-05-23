<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending')->after('actioned_by');
            $table->text('refuse_reason')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('penalties', function (Blueprint $table) {
            $table->dropColumn(['status', 'refuse_reason']);
        });
    }
};
