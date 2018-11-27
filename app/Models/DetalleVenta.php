<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Uuid;

class DetalleVenta extends Model
{
  use SoftDeletes;

  protected $table = "detalle_ventas";

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
      'venta_uuid', 'articulo_uuid', 'cantidad', 'precio', 'descuento'
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

  public function venta()
  {
    return $this->belongsTo('App\Models\Venta','venta_uuid','uuid');
  }

  public function articulo()
  {
    return $this->belongsTo('App\Models\Articulo','articulo_uuid','uuid');
  }
}
