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
        'price', 'quantity', 'type_id', 'station_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function typename() {
        return $this->HasOne('App\Models\Type', 'id', 'type_id');
        // TODO make relationship with Auth Station
    }

    // protected $appends = ['_links'];
    // /**
    //  * Set attributes links
    //  *
    //  * @return array
    //  */
    // public function getLinksAttribute()
    // {
    //     return [
    //         'self' => app()->make('url')->to("/api/v1/type/{$this->attributes['type_id']}")
    //     ];
    // }
}