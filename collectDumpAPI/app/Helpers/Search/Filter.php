<?php

namespace App\Helpers\Search\StationSellSearch\Filters;

use Illuminate\Database\Eloquent\Builder;

interface Filter 
{
  public static function apply(Builder $builder, $query);
}
?>