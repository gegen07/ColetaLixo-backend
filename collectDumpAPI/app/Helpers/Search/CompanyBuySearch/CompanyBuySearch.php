<?php
namespace App\Helpers\Search\CompanyBuySearch;

use App\Models\CompanyBuy;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class CompanyBuySearch
{
  public static function apply(Request $filters) {
    $query = static::applyDecoratorFromRequest($filters, (new StationSell)->newQuery());

    return static::getResults($query);
  }

  private static function applyDecoratorFromRequest(Request $request, Builder $query) {
    foreach ($request->all() as $filterName => $value) {
      $decorator = static::createFilterDecorator($value);

      if (static::isValidDecorator($decorator)) {
        $query = $decorator::apply($query, $value);
      }
    }

    return $query;
  }

  private static function createFilterDecorator($name) {
    return __NAMESPACE__ . '\\Filters\\' . studly_case($name);
  }

  private static function isValidDecorator($decorator) {
    return class_exists($decorator);
  }

  private static function getResults(Builder $query) {
    return $query->get();
  }
}

?>
