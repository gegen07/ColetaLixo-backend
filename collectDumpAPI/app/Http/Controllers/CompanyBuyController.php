<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\CompanyBuy;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\CompanyBuyTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;

class CompanyBuyController extends Controller{

    use Helpers;

    public function __construct()
    {
      $this->middleware('auth:api');
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
			return $this->response->item($companyBuy, new CompanyBuyTransformer);
		} else {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new buy of company.', $validator->errors());
		}

	}

	public function update(Request $request, $id)
  {
    $request->user()->authorizeRoles(['company']);

    try {
      $companyBuy = CompanyBuy::findOrFail($id);

      if ($request->has('stationSell_id')) {
          $companyBuy->station_id = $request->input('stationSell_id');
      }
      if($request->has('company_id')) {
          $companyBuy->company_id = $request->input('company_id');
      }
      $companyBuy->save();
      return $this->response->item($companyBuy, new CompanyBuyTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
      throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found buy of company');
    }
	}

	public function delete(Request $request, $id)
  {
    $request->user()->authorizeRoles(['company']);
    $companyBuy = CompanyBuy::destroy($id);
		if($companyBuy) {
			return response(
				[
					'status' => $companyBuy ? "success" : "Not found.",
				], $statusCode ?? 201
			);
		} else {
		   throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found buy of company');
		}
	}

	public function index(Request $request)
  {
    $request->user()->authorizeRoles(['company']);

    if ($request->has('limit')) {
      $companyBuy = CompanyBuy::paginate($request->input('limit'));
    } else {
      $companyBuy = CompanyBuy::paginate($request->input(12));
    }


    return $this->response->paginator($companyBuy, new CompanyBuyTransformer);
  }

  public function show(Request $request, $id)
  {
    $request->user()->authorizeRoles(['company']);
    try {
        $companyBuy = CompanyBuy::with('station')->findOrFail($id);
        return $this->response->item($companyBuy, new CompanyBuyTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
        throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not buy of company');
    }
  }
}
?>
