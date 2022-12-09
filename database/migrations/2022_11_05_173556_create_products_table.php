<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Creación de tabla PRODUCTS
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // Nombre de máx 100 caracteres, descripción, precio (de 8 cifras y 2 décimas)
            $table->string('name', 100);
            // Campo sin límite de texto
            $table->text('description');
            // 8 cifras. Las dos últimas para usar como decimales
            $table->decimal('price', $precision = 8, $scale = 2);
            // Columna de stock es un número entero
            $table->integer('stock');
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
        Schema::dropIfExists('products');
    }
};
