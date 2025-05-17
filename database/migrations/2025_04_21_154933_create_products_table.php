<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('brand')->nullable();
            $table->string('gender')->nullable(); // male, female, kids
            $table->json('clothing_sizes')->nullable();  // ["XS", "S", "M", ...]
            $table->json('shoe_sizes')->nullable();      // [36, 37, 38, ...]
            $table->string('color')->nullable();
            $table->integer('price');
            $table->string('image')->nullable();  // путь к изображению
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
