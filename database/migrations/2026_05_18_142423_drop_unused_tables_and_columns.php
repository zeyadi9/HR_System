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
        // Drop foreign keys and unused columns from 'check_in_outs'
        if (Schema::hasTable('check_in_outs')) {
            Schema::table('check_in_outs', function (Blueprint $table) {
                if (Schema::hasColumn('check_in_outs', 'device_id')) {
                    $table->dropForeign(['device_id']);
                    $table->dropColumn('device_id');
                }
                if (Schema::hasColumn('check_in_outs', 'user_agent')) {
                    $table->dropColumn('user_agent');
                }
                if (Schema::hasColumn('check_in_outs', 'verification_method')) {
                    $table->dropColumn('verification_method');
                }
            });
        }

        // Drop tables
        Schema::dropIfExists('employee_devices');
        Schema::dropIfExists('notifications');

        // Drop unused columns from 'users'
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'whatsapp_number')) {
                    $table->dropColumn('whatsapp_number');
                }
                if (Schema::hasColumn('users', 'whatsapp_password')) {
                    $table->dropColumn('whatsapp_password');
                }
                if (Schema::hasColumn('users', 'onesignal_id')) {
                    $table->dropColumn('onesignal_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

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

        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp_number')->nullable();
            $table->string('whatsapp_password')->nullable();
            $table->string('onesignal_id')->nullable();
        });

        Schema::table('check_in_outs', function (Blueprint $table) {
            $table->foreignId('device_id')->nullable()->after('user_id')->constrained('employee_devices')->onDelete('set null');
            $table->text('user_agent')->nullable()->after('created_ip');
            $table->string('verification_method')->default('manual')->after('user_agent');
        });
    }
};
