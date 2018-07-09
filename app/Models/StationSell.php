<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;


class StationSell extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'stationSell';
    protected $fillable = [
        'price', 'quantity', 'type_id', 'idStation'
    ];

    public function typename() {
        return $this->HasOne('App\Models\Type', 'id');
        // TODO make relationship with Auth Station
    }
}