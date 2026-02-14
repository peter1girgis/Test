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
        Schema::create('website_settings', function (Blueprint $table) {
            $table->id();
            $table->string('main_logo')->nullable(); // صورة اللوجو
            $table->integer('products_per_page')->default(12);
            $table->integer('home_products_count')->default(8);
            $table->integer('home_ads_count')->default(3);
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();
            $table->text('footer_text')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_settings');
    }
};
