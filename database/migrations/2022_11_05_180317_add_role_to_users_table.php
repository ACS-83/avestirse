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
        // Modifica la tabla USERS para añadir una columna de roles
        Schema::table('users', function (Blueprint $table) {
            // Añade rol de usuario (0 por defecto = usuario. 1 = admin)
            $table->integer('role')->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //Elimina columna ROLE
            $table->dropColumn('role');
        });
    }
};
