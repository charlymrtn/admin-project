<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetalleIngresosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_ingresos', function (Blueprint $table) {
            $table->uuid('uuid')->primary();

            $table->uuid('ingreso_uuid');
            $table->uuid('articulo_uuid');

            $table->integer('cantidad');
            $table->decimal('precio',11,2);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['ingreso_uuid','articulo_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_ingresos');
    }
}
