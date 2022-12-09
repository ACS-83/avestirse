<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Products;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creación de tabla IMAGES
        Schema::create('images', function (Blueprint $table) {
            // Número de ID
            $table->id();
            // Llave foránea hacia PRODUCTS
            $table->foreignIdFor(Products::class);
            // La columna IMAGE puede estar vacía
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
};
