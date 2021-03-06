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
            'price' => $stationSell->price,
            'quantity' => $stationSell->quantity,
            'type'   => [
              'link' => '/api/v1/types/' . $stationSell->type_id

            ],
            'station' => [
                'link' => '/api/v1/user/station/' . $stationSell->station_id
            ]
        ];
    }
}
