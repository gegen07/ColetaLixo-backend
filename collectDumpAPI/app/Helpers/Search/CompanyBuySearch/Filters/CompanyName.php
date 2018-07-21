<?php

namespace App\Helpers\Search\CompanyBuySearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class Name implements Filter {
  
  public static function apply(Builder $builder, $value) 
  {
    return $builder->whereHas('company', function($query) use ($value) {
      $query->where('name', 'like', "$" . $value . "%");
    });
  } 
}
?>