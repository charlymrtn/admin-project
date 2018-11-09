<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Uuid;

class Rol extends Model
{
  protected $table = "roles";

  public $incrementing = false;
  protected $primaryKey = 'uuid';
  protected $keyType = 'uuid';

  protected $fillable = [
      'uuid', 'nombre', 'descripcion'
  ];

  public $timestamps = false;

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

  public function usuarios()
  {
    return $this->hasMany('App\User','uuid','uuid');
  }
}
