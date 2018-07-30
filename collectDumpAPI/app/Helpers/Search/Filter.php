<?php

namespace App\Helpers\Search;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
  public static function apply(Builder $builder, $query);
}
?>
