<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Webpatser\Uuid\Uuid;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('nombre',30)->unique();
            $table->string('descripcion',100)->nullable();
            $table->boolean('condicion')->default(1);

        });

        DB::table('roles')->insert([
          'uuid' => Uuid::generate(4)->string,
          'nombre' => 'Administrador',
          'descripcion' => 'Administradores de área'
        ]);

        DB::table('roles')->insert([
          'uuid' => Uuid::generate(4)->string,
          'nombre' => 'Vendedor',
          'descripcion' => 'Vendedor área venta'
        ]);

        DB::table('roles')->insert([
          'uuid' => Uuid::generate(4)->string,
          'nombre' => 'Personal Almacén',
          'descripcion' => 'Almacen área compras'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
