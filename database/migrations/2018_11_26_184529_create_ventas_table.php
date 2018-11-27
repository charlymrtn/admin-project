<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
          $table->uuid('uuid')->primary();

          $table->uuid('cliente_uuid');
          $table->uuid('usuario_uuid');

          $table->string('tipo_comprobante',20);
          $table->string('serie_comprobante',7)->nullable();
          $table->string('num_comprobante',10);
          $table->datetime('fecha_venta');
          $table->decimal('impuesto',4,2);
          $table->decimal('total',11,2);
          $table->string('estado',20);

          $table->timestamps();
          $table->softDeletes();

          $table->index(['cliente_uuid','usuario_uuid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventas');
    }
}
