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
        // Creación de tabla ORDERS (pedidos)
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // Mail de usuario
            $table->string('mailuser');
            // La columna nombre soporta un máximo de 100 caracteres
            $table->string('name', 100);
            // La columna apellidos soporta un máximo de 100 caracteres
            $table->string('surname', 100);
            // La columna dirección soporta un máximo de 200 caracteres
            $table->text('address', 200);
            // Máximo 100 caracteres para país
            $table->string('country', 100);
            // Máximo de 6 caracteres para código postal. UNSIGNED para hacer entender
            // a Laravel que no es una columna autoincrementable
            $table->integer('zip')->length(6)->unsigned();
            // 10 caracteres para almacenar método de pago
            $table->string('paymentMethod', 10);
            // Nombre completo de tarjeta
            $table->string('fullCardName');
            // Número de tarjeta. bigInteger para almacenar números grandes. UNSIGNED = no autoincrementable
            $table->bigInteger('cardNumber')->unsigned();
            // Máximo de 4 cifras para expiración de tarjeta. UNSIGNED = no autoincrementable
            $table->integer('cardExpiration')->length(4)->unsigned();
            // Máximo de 3 cifras para CVV de tarjeta. UNSIGNED = no autoincrementable
            $table->integer('cvv')->length(3)->unsigned();
            // Número decimal para almacenar el precio total del pedido
            $table->decimal('orderTotalPrice');
            // Cadena que recoge los productos que se han pedido (en formato array)
            $table->text('productsOrdered');
            // 0 o 1 para comprobar los envíos de producto
            $table->boolean('sent');
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
        Schema::dropIfExists('orders');
    }
};
