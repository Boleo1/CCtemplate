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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title', 160);
            $table->string('slug')->unique();
            $table->longText('description')->nullable();
            $table->dateTimeTz('start_at');
            $table->dateTimeTz('end_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->dateTimeTz('published_at')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->foreignId('created_by')->costrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->costrained('users')->nullOnDelete();
            $table->softDeletes();

            
        });
      }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
