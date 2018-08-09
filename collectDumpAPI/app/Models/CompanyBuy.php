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

    public function stationSell() {
        return $this->belongsTo(StationSell::class, 'id', 'stationSell_id');
    }
    public function company() {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }
}
