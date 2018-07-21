<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


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
        return $this->HasOne(Type::class, 'id', 'type_id');
    }

    public function station() {
        return $this->HasOne(User::class, 'id', 'station_id');
    }
}
