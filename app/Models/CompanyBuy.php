<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CompanyBuy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'companyBuys';
    protected $fillable = [
        'id', 'stationSell_id', 'company_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function station() {
        return $this->HasOne('App\Models\StationSell', 'id', 'stationSell_id');
        // TODO make relationship with Auth company
    }
}