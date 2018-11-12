<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Webpatser\Uuid\Uuid;
use App\Models\Rol;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = "usuarios";

    public $incrementing = false;
    protected $primaryKey = 'uuid';
    protected $keyType = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid', 'usuario', 'password', 'rol_uuid', 'condicion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public $timestamps = false;

    public function rol()
    {
      return $this->belongsTo('App\Models\Rol','rol_uuid','uuid');
    }

    public function persona()
    {
      return $this->belongsTo('App\Models\Persona','uuid','uuid');
    }

    public funcion getRolIdAttribute()
    {
      $rol == Rol::where($this->rol_uuid)->first();

      if ($rol) {
        if ($rol->nombre == 'Administrador') {
          return 1;
        }elseif ($rol->nombre == 'Vendedor') {
          return 2;
        }elseif ($rol->nombre == 'Vendedor') {
          return 3;
        }
      }

      return 0;
    }

    public funcion getRolNombreAttribute()
    {
      $rol == Rol::where($this->rol_uuid)->first();

      if ($rol) {
        return $rol->nombre;
      }

      return 'Desconocido';
    }

}
