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
            $table->string('requested_by', 160);
            $table->string('event_type', 100);
            $table->longText('description')->nullable();
            $table->dateTimeTz('start_at');
            $table->dateTimeTz('end_at')->nullable();
            $table->boolean('all_day')->default(false);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->dateTimeTz('published_at')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->softDeletes();
            $table->unsignedInteger('sort_order')->default(999999)->index(); 
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->string('thumbnail_image_path')->nullable();
            
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
