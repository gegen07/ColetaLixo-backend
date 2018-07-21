<?php 

namespace App\Helpers\Search\CompanyBuySearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class PriceLessThan implements Filter 
{
  public static function apply(Builder $builder, $values) 
  {
    return $builder->whereHas('stationSell', function ($query) use ($values) {
        $query->where('price', '<', $values);  
    });
  }
}
?>