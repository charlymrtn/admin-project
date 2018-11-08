<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class Proveedor extends Model
{
  protected $table = "proveedores";

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
      'uuid', 'contacto', 'telefono_contacto'
  ];

  public $timestamps = false;

  public function persona()
  {
    return $this->belongsTo('App\Models\Persona','uuid','uuid');
  }
}
