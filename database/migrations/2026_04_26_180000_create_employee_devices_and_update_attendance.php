<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('device_token_hash')->unique();
            $table->text('user_agent')->nullable();
            $table->string('device_info')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();
        });

        Schema::table('check_in_outs', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()->after('user_id')->constrained('employee_devices')->onDelete('set null');
            $table->text('user_agent')->nullable()->after('created_ip');
            $table->string('verification_method')->default('manual')->after('user_agent');
        });
    }

    public function down(): void
    {
        Schema::table('check_in_outs', function (Blueprint $table) {
            $table->dropConstrainedForeignId('device_id');
            $table->dropColumn(['user_agent', 'verification_method']);
        });
        Schema::dropIfExists('employee_devices');
    }
};
