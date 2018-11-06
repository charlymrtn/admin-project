<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Categoria extends Model
{
    use SoftDeletes;

    protected $table = "categorias";

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre', 'descripcion', 'condicion'
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
}
