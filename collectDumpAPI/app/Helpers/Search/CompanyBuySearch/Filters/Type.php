<?php 

namespace App\Helpers\Search\CompanyBuySearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class Type implements Filter 
{
  public static function apply(Builder $builder, $values) 
  {
    return $builder->whereHas('stationSell', function ($query) {
      $query->whereHas('typename', function($join) use ($values)  {
        $join->where('type', 'like', "%" . $values . "%");
      });     
    });
  }
}
?>