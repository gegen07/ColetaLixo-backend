<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Models\StationSell;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class StationSellController extends Controller{

	public function create(Request $request){

        $this->validate($request, [
            'price'     => 'required',
            'quantity'  => 'required',
            'type_id'   => 'required',
        ]);
 
    	$stationSell = StationSell::create($request->all());
 
    	return response()->json(['data' => $stationSell]);
 
	}
 
	public function update(Request $request, $id){

        $this->validate($request, [
            'price'     => 'required',
            'quantity'  => 'required',
            'type_id'   => 'required',
        ]);

    	$stationSell = StationSell::find($id);
        $stationSell->price = $request->input('price');
        $stationSell->quantity = $request->input('quantity');
        $stationSell->type_id = $request->input('type_id');
        $stationSell->station_id = $request->input('station_id');
    	$stationSell->save();
 
    	return response()->json(['data' => $stationSell]);
	}  

	public function delete($id){

        try {
            $stationSell = Type::findOrFail($id);
            $stationSell->delete();
        } catch(\Exception $e) {
                $stationSell = null;
                $statusCode = 404;
        }
 
    	return response()->json([
            "data" => $stationSell,
            "status" => $stationSell ? "success" : "Not found."
        ], $statusCode ?? 200
        );
	}

	public function index(){
 
    	return response()->json(['data'=> StationSell::with('typename')->get()]);
 
    }

    public function show($id) {
        $stationSells = StationSell::with('type')->findOrFail($id);
        return response()->json($stationSells);        
    }

    public function searchTypeName($name) {
        $type = DB::table('type')
                ->where('type', '=', $name)
                ->first();  

        $stationSells = StationSell::with('typename')
                            ->where('type_id', '=', $type->id)
                            ->get(); 
        
        return response()->json([
            'data'=> $stationSells
            ]);        
    }
}
?>