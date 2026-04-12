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
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('reunion_id')->constrained('reunions')->onDelete('cascade');
            $table->enum('response_status', ['pending', 'accepted', 'declined'])->default('pending');
            $table->boolean('presence')->nullable();
            $table->timestamps();
            
            $table->unique(['user_id', 'reunion_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
