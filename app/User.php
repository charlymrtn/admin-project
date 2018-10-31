<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

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
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'deleted_at'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
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
