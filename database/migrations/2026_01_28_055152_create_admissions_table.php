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
        Schema::create('admissions', function (Blueprint $table) {
            $table->uuid();
            $table->foreignUuid('patient_id')->constrained();
            $table->foreignUuid('bed_id')->constrained();
            
            $table->string('status')->index(); 
            
            $table->timestamp('expected_arrival_at')->nullable(); 

            $table->timestamp('admitted_at')->nullable();
            $table->timestamp('discharged_at')->nullable();
            
            $table->text('notes')->nullable(); 
            $table->timestamps();

            $table->index(['bed_id', 'status']); 
            $table->index('expected_arrival_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admissions');
    }
};
