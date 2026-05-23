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
        Schema::create('check_in_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('name');
            $table->date('date');
            $table->string('day');
            $table->enum('type', ['حضور', 'انصراف']);
            $table->enum('status', ['pending', 'accepted', 'refused'])->default('pending');
            $table->text('refuse_reason')->nullable();
            $table->string('actioned_by')->nullable();
            $table->timestamps();
            $table->string('created_ip', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('check_in_outs');
    }
};
