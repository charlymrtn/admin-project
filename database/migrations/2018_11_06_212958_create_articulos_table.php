<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticulosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->uuid('categoria_uuid');

            $table->string('codigo',50)->nullable();
            $table->string('nombre',100)->unique();
            $table->decimal('precio',11,2);
            $table->integer('existencias');
            $table->string('descripcion',256)->nullable();
            $table->boolean('condicion')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['categoria_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articulos');
    }
}
