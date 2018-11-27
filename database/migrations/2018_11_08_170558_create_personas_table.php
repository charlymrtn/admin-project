<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personas', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->string('nombre',100);
            $table->string('email',50)->unique();
            $table->string('tipo_documento',20)->nullable();
            $table->string('num_documento',20)->nullable();
            $table->string('direccion',70)->nullable();
            $table->string('telefono',20)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('personas');
    }
}
