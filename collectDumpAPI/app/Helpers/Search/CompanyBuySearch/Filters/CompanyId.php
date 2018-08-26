<?php

namespace App\Helpers\Search\CompanyBuySearch\Filters;
use Illuminate\Database\Eloquent\Builder;
use App\Helpers\Search\Filter;

class CompanyId implements Filter
{
  public static function apply(Builder $builder, $values)
  {
    return $builder->orderBy('company_id', $values);
  }
}
?>
