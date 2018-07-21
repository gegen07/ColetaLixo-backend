<?php 

namespace App\Helpers\Search\StationSellSearch\Filters;
use Illuminate\Database\Eloquent\Builder;

class Type implements Filter 
{
  public static function apply(Builder $builder, $values) 
  {
    return $builder->whereHas('typename', function ($query) use ($values){
      $query->has('name', 'like', "%" . $values . "%");
    });
  }
}
?>