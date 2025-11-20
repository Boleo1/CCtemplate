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
        $table->string('event_name');

        $table->string('event_type');
        $table->string('requested_by');

        // Date / time fields
        $table->date('event_date');
        $table->date('end_date')->nullable();

        $table->time('event_time')->nullable();
        $table->time('end_time')->nullable();
        $table->boolean('all_day')->default(false);

        // Description
        $table->longText('event_description');

        // Moderation / workflow
        $table->enum('status', ['pending', 'approved', 'rejected'])
              ->default('pending')
              ->index();

        $table->foreignId('reviewed_by')
              ->nullable()
              ->constrained('users')
              ->nullOnDelete();

        $table->timestamp('reviewed_at')->nullable();
        $table->text('review_notes')->nullable();

        $table->softDeletes();

        // Sorting
        $table->unsignedInteger('sort_order')
              ->default(999999)
              ->index();
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
