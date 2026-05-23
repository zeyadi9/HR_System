<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ملاحظات السوبر ادمن
        Schema::create('admin_notes', function (Blueprint $table) {
            $table->id();
            $table->text('note');
            $table->date('date');
            $table->timestamps();
            $table->string('created_ip', 45)->nullable();
        });

        // 2. الحوافز
        Schema::create('incentives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('evaluation');
            $table->date('date');
            $table->timestamps();
            $table->string('created_ip', 45)->nullable();
        });

        // 3. التسويات
        Schema::create('settlements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->text('note');
            $table->date('date');
            $table->string('day');
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending');
            $table->text('accept_note')->nullable();
            $table->text('refuse_reason')->nullable();
            $table->string('actioned_by')->nullable();
            $table->timestamps();
            $table->string('created_ip', 45)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_notes');
        Schema::dropIfExists('incentives');
        Schema::dropIfExists('settlements');
    }
};
