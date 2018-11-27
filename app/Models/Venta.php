<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;

class Venta extends Model
{
  use SoftDeletes;

  protected $table = "ventas";

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
      'cliente_uuid', 'usuario_uuid', 'tipo_comprobante',
      'serie_comprobante', 'num_comprobante', 'fecha_venta', 'impuesto', 'total', 'estado'
  ];

  protected $dates = [
      'created_at', 'updated_at', 'deleted_at'
  ];

  protected $hidden = [
      'deleted_at'
  ];

  public static function boot()
  {
    parent::boot();
    self::creating(function ($model){
      if(empty($model->uuid))
      {
        $model->uuid = Uuid::generate(4)->string;
      }
    });
  }

  public function detalles()
  {
    return $this->hasMany('App\Models\DetalleVenta','ingreso_uuid','uuid');
  }

  public function usuario()
  {
    return $this->belongsTo('App\User','usuario_uuid','uuid');
  }

  public function cliente()
  {
    return $this->belongsTo('App\Models\Persona','cliente_uuid','uuid');
  }
}
