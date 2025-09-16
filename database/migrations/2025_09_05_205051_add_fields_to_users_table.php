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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('community_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('role', ['admin','staff','viewer'])->default('staff');
            $table->index(['community_id','role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('community_id');
            $table->dropColumn('role');
        });
    }
};
