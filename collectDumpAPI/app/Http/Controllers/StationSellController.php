<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Models\StationSell;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Transformers\StationSellTransformer;
use Dingo\Api\Routing\Helpers;
use Illuminate\Support\Facades\Validator;
  
class StationSellController extends Controller{

    use Helpers;

	public function create(Request $request){

		$rules = [
            'price' => ['required'],
            'quantity' => ['required'],
            'type_id' => ['required'],
            'idStation' => ['required']
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
            if($request->has('idStation')){
                $stationSell->station_id = $request->input('idStation');
            }
            $stationSell->save();
            return $this->response->item($stationSell, new StationSellTransformer)->setStatusCode(200);
        } catch (\Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found sell of station');
        }
	}  

	public function delete($id){

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

	public function index(){
        $stationSell = StationSell::paginate(12);
		return $this->response->paginator($stationSell, new StationSellTransformer);  
    }

    public function show($id) {
        try {
            $stationSell = StationSell::with('typename')->findOrFail($id);
            return $this->response->item($stationSell, new StationSellTransformer)->setStatusCode(200);
        } catch (\Exception $e) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found sell of station');
        }       
    }

    public function searchTypeName($name) {
        $type = DB::table('type')
                ->where('type', '=', $name)
                ->first();  

        $stationSells = StationSell::with('typename')
                            ->where('type_id', '=', $type->id)
                            ->paginate(12); 
        
        return $this->response->paginator($stationSells, new StationSellTransformer);     
    }
}
?>