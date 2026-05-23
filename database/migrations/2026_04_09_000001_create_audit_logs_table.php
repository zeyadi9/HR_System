<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_name');
            $table->string('action');
            $table->string('module');
            $table->string('target');
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('audit_logs');
    }
};
