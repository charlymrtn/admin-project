<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Webpatser\Uuid\Uuid;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->uuid('uuid');

            $table->string('usuario')->unique();
            $table->string('password');
            $table->boolean('condicion')->default(1);

            $table->uuid('rol_uuid');

            $table->rememberToken();

            $table->index(['uuid','rol_uuid']);
        });

        $rol = DB::table('roles')->select('uuid')->where('nombre','=','Administrador')->first();
        $rol = $rol->uuid;

        DB::table('usuarios')->insert([
          'uuid' => Uuid::generate(4)->string,
          'usuario' => 'pajaro',
          'password' => bcrypt('123'),
          'rol_uuid' => $rol
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios');
    }
}
