<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\StationSellTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;

use App\Models\StationSell;
use App\Models\Role;
use App\Models\User;
use App\Helpers\Search\StationSellSearch\StationSellSearch;

class StationSellController extends Controller{

  use Helpers;

  public function __construct()
  {
    $this->middleware('auth:api');
  }

	public function create(Request $request){
    $request->user()->authorizeRoles(['station']);
		$rules = [
            'price' => ['required'],
            'quantity' => ['required'],
            'type_id' => ['required'],
            'station_id' => ['required']
		];
		$validator = Validator::make($request->all(), $rules);

		if (!$validator->fails()) {
			$stationSell = StationSell::create($request->all());
			return $this->response->item($stationSell, new StationSellTransformer);
		} else {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new sell of station.', $validator->errors());
		}

	}

	public function update(Request $request, $id){
    $request->user()->authorizeRoles(['station']);
    try {
      $stationSell = StationSell::findOrFail($id);

      if ($request->has('price')) {
        $stationSell->price = $request->input('price');
      }
      if($request->has('quantity')) {
        $stationSell->quantity = $request->input('quantity');
      }
      if($request->has('type_id')) {
        $stationSell->type_id = $request->input('type_id');
      }
      if($request->has('station_id')){
        $stationSell->station_id = $request->input('station_id');
      }
      $stationSell->save();
      return $this->response->item($stationSell, new StationSellTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found sell of station');
    }
	}

	public function delete(Request $request, $id)
  {
    $request->user()->authorizeRoles(['station']);
    $stationSell = StationSell::destroy($id);
		if($stationSell) {
			return response(
				[
					'status' => $stationSell ? "success" : "Not found.",
				], $statusCode ?? 201
			);
		} else {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found sell of station');
		}
	}

	public function index(Request $request)
  {
    $request->user()->authorizeRoles(['station', 'company']);

    if($request->has('type')) {
      $stationSell = searchTypeName($request, $request->input('type'));
    }

    if ($request->has('station_name')) {
      $stationSell = searchStationName($request, $request->input('station_name'));
    }

    if ($request->has('limit')) {
      $stationSell = StationSell::paginate($request->input('limit'));
    } else {
      $stationSell = StationSell::paginate(12);
    }

    return $this->response->paginator($stationSell, new StationSellTransformer);
  }

  public function show(Request $request, $id) {
    $request->user()->authorizeRoles(['station', 'company']);
    try {
      $stationSell = StationSell::findOrFail($id);
      return $this->response->item($stationSell, new StationSellTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found sell of station');
    }
  }

  public function search(Request $request)
  {
    return StationSellSearch::apply($request);
  }
}
?>
