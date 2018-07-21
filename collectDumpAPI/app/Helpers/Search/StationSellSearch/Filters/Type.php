<?php 

namespace App\Helpers\Search\StationSellSearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class Type implements Filter 
{
  public static function apply(Builder $builder, $values) 
  {
    return $builder->whereHas('typename', function ($query) use ($values){
      $query->where('type', 'like', "%" . $values . "%");
    });
  }
}
?>