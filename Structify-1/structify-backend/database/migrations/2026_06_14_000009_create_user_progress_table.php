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
    Schema::create('user_progress', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->foreignId('topic_id')->constrained()->cascadeOnDelete();
        $table->boolean('is_completed')->default(false);
        $table->integer('completion_percentage')->default(0);
        $table->timestamp('last_visited_at')->nullable();
        $table->timestamps();
        $table->unique(['user_id', 'topic_id']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
