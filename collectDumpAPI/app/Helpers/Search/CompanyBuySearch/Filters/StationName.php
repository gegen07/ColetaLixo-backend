<?php

namespace App\Helpers\Search\CompanyBuySearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class StationName implements Filter {

  public static function apply(Builder $builder, $value)
  {
    return $builder->whereHas('stationSell', function($query) {
      $query->whereHas('station', function($join) use ($value)  {
        $query->where('name', 'like', "$" . $value . "%");
      });
    });
  }
}
?>
