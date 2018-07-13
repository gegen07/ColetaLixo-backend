<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\StationSell;

class StationSellTransformer extends TransformerAbstract
{
    public function transform(StationSell $stationSell)
    {
        return [
            'id' => $stationSell->id,
            'quantity' => $stationSell->quantity,
            'type'   => [
              'link' => '/api/v1/types/' . $stationSell->type_id
              
            ],
            'station' => [
                'link' => '/api/v1/station/' . $stationSell->idStation 
            ]
        ];
    }
}