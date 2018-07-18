<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Type extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'type';
    protected $fillable = ['type'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];
}