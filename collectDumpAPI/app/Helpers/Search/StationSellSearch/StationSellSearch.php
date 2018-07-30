<?php
namespace App\Helpers\Search\StationSellSearch;

use App\Models\StationSell;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

/**
 *
 */
class StationSellSearch
{
  public static function apply(Request $filters) {
    $query = static::applyDecoratorFromRequest($filters, (new StationSell)->newQuery());

    return static::getResults($filters, $query);
  }

  private static function applyDecoratorFromRequest(Request $request, Builder $query) {
    foreach ($request->all() as $filterName => $value) {
      $decorator = static::createFilterDecorator($filterName);

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

  private static function hasPagination(Request $filters) {
    if($filters->has('limit')) {
      return true;
    }
    return false;
  }

  private static function getResults(Request $filters, Builder $query) {
    if (static::hasPagination($filters)) {
      return $query->paginate($filters->input('limit'));
    }

    return $query->get();
  }
}

?>
