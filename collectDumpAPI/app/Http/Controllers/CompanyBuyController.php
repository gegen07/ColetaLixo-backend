<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Models\CompanyBuy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\CompanyBuyTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
  
class CompanyBuyController extends Controller{

    use Helpers;

	public function create(Request $request){

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
 
	public function update(Request $request, $id){

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

	public function delete($id){

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

	public function index(){
        $companyBuy = CompanyBuy::paginate(12);
		return $this->response->paginator($companyBuy, new CompanyBuyTransformer);  
    }

    public function show($id) {
        try {
            $companyBuy = CompanyBuy::with('station')->findOrFail($id);
            return $this->response->item($companyBuy, new CompanyBuyTransformer)->setStatusCode(200);
        } catch (\Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not buy of company');
        }       
    }
}
?>