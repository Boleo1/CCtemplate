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
        Schema::create('requests', function (Blueprint $table) {
          $table->id();
          $table->timestamps();
          $table->string('event_type');
          $table->string('requested_by');
          $table->date('event_date');
          $table->time('event_time');
          $table->longText('event_description');
          $table->enum('status', ['pending','approved','rejected'])->default('pending')->index();
          $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
          $table->timestamp('reviewed_at')->nullable();
          $table->text('review_notes')->nullable();
          $table->softDeletes();
          $table->unsignedInteger('sort_order')->default(999999)->index(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
