<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        // Force nullable directly via raw SQL — works regardless of FK state
        DB::statement('ALTER TABLE `overtimes` MODIFY `user_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE `leaves` MODIFY `user_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE `permissions` MODIFY `user_id` BIGINT UNSIGNED NULL');

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::statement('ALTER TABLE `overtimes` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `leaves` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `permissions` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');

        Schema::enableForeignKeyConstraints();
    }
};
