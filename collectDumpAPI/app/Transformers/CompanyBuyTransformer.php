<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\CompanyBuy;

class CompanyBuyTransformer extends TransformerAbstract
{
    public function transform(CompanyBuy $companyBuy)
    {
        return [
            'id' => $companyBuy->id,
            'stationSell'   => [
              'link' => '/api/v1/stationSells/' . $companyBuy->stationSell_id            
            ],
            'station' => [
                'link' => '/api/v1/user/company/' . $companyBuy->company_id 
            ]
        ];
    }
}