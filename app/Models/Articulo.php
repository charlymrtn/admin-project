<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Articulo extends Model
{
  use SoftDeletes;

  protected $table = "articulos";

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
      'nombre', 'categoria_uuid', 'codigo', 'descripcion', 'condicion', 'existencias', 'precio'
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

  public function categoria()
  {
    return $this->belongsTo('App\Models\Categoria','categoria_uud','uuid');
  }
}
