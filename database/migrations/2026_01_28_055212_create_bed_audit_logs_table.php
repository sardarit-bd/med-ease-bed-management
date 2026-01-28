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
        Schema::create('bed_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('bed_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable();
            
            $table->string('previous_status');
            $table->string('new_status');
            $table->timestamp('changed_at');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bed_audit_logs');
    }
};
