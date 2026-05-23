<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // تعديل عمود role ليقبل super_admin أيضاً
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user', 'super_admin') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        // إعادة العمود لحالته الأصلية بدون super_admin
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') NOT NULL DEFAULT 'user'");
    }
};
