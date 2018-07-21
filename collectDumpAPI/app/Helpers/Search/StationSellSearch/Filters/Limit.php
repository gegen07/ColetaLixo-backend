<?php 

namespace App\Helpers\Search\StationSellSearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class Limit implements Filter 
{
  public static function apply(Builder $builder, $values) 
  {
    return $builder->paginate($values);
  }
}
?>