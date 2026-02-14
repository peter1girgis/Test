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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();   // phone, receipt, payment_id, ...
            $table->string('label');           // "Phone Number", "Receipt Image"
            $table->enum('type',['phone','file','text','textarea','date','number']);            // text, phone, file, number, date, textarea, select ...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
