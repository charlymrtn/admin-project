<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'type',
        'notifiable_id',
        'notifiable_type',
        'data'
    ];

    protected $dates = [
        'created_at', 'updated_at', 'deleted_at', 'read_at'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $casts = [
        'type' => 'string',
        'notifiable_id' => 'uuid',
        'notifiable_type' => 'string',
        'data' => 'string',
        'id' => 'uuid'

    ];
}
