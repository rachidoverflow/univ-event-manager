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
        // Update User roles
        Schema::table('users', function (Blueprint $table) {
            // Native change might work in Laravel 12 for MySQL, but let's be safe or use string if enum is a pain
            $table->string('role')->default('enseignant')->change(); 
        });

        // Update Reunions table
        Schema::table('reunions', function (Blueprint $table) {
            $table->foreignId('instance_id')->nullable()->constrained('instances')->onDelete('set null');
            $table->enum('type', ['standard', 'elargie'])->default('standard');
            $table->text('invitation_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reunions', function (Blueprint $table) {
            $table->dropForeign(['instance_id']);
            $table->dropColumn(['instance_id', 'type', 'invitation_content']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'participant'])->default('participant')->change();
        });
    }
};
