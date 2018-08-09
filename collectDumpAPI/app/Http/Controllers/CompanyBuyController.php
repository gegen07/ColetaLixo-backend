<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CompanyBuy;
use App\Models\StationSell;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\CompanyBuyTransformer;
use Illuminate\Support\Facades\Auth;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;

use App\Helpers\Search\CompanyBuySearch\CompanyBuySearch;

class CompanyBuyController extends Controller{

    use Helpers;

  public function __construct()
  {
    $this->middleware('auth:api');
  }

  public function guard()
  {
      return Auth::guard('api');
  }

	public function create(Request $request)
  {
    $request->user()->authorizeRoles(['company']);

		$rules = [
            'stationSell_id' => ['required'],
            'company_id' => ['required'],
    ];

		$validator = Validator::make($request->all(), $rules);

		if (!$validator->fails()) {
			$companyBuy = CompanyBuy::create($request->all());
			return $this->response->item($companyBuy, new CompanyBuyTransformer)->setStatusCode(200);
		} else {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new buy of company.', $validator->errors());
		}

	}

	public function delete(Request $request, $id)
  {
    $request->user()->authorizeRoles(['company']);
    $companyBuy = CompanyBuy::destroy($id);
		if($companyBuy) {
			return response(
				$statusCode ?? 204
			);
		} else {
		   throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found buy of company');
		}
	}

	public function index(Request $request)
  {
    $request->user()->authorizeRoles(['company']);

    $companyBuys = CompanyBuySearch::apply($request);
    
    $companyBuys->whereHas('company', function($query){
      $query->where('email', $this->guard()->user()->email);
    });

    if(CompanyBuySearch::hasPagination($request)) {
      return $this->response->paginator($companyBuys->paginate($request->input('limit')), new CompanyBuyTransformer)->setStatusCode(200);
    }

    return $this->response->collection($companyBuys->get(), new CompanyBuyTransformer)->setStatusCode(200);
  }

  public function show(Request $request, $id)
  {
    $request->user()->authorizeRoles(['company']);
    try {
        $companyBuy = CompanyBuy::findOrFail($id);
        return $this->response->item($companyBuy, new CompanyBuyTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not buy of company');
    }
  }

  private function buy(Request $request) {
    $stationSell = StationSell::findOrFail($request->stationSell_id);
    $stationSell->isSelled = 1;
    $stationSell->save();
  }
}
?>
