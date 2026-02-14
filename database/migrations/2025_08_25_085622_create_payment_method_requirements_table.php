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
        Schema::create('payment_method_requirements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_method_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('requirement_id')
                ->constrained()
                ->cascadeOnDelete();

            // Pivot fields
            $table->boolean('is_required')->default(true);
            $table->string('width')->default('full');
            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_requirements');
    }
};
