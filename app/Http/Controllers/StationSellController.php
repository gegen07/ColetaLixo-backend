<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\DB;
use App\Models\StationSell;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class StationSellController extends Controller{

	public function create(Request $request){
 
    	$stationSell = StationSell::create($request->all());
 
    	return response()->json($stationSell);
 
	}
 
	public function update(Request $request, $id){

    	$stationSell = StationSell::find($id);
        $stationSell->price = $request->input('price');
        $stationSell->quantity = $request->input('quantity');
        $stationSell->type_id = $request->input('type_id');
        $stationSell->station_id = $request->input('station_id');
    	$stationSell->save();
 
    	return response()->json($stationSell);
	}  

	public function delete($id){
    	$stationSell = StationSell::find($id);
    	$stationSell->delete();
 
    	return response()->json('Removed successfully.');
	}

	public function index(){
 
    	$stationSell = DB::table('stationSell')
                        ->join('type', 'type.id', '=', 'stationSell.type_id')
                        ->select('stationSell.*', 'type.type')
                        ->get(); 
 
    	return response()->json($stationSell);
 
    }

    public function searchTypeName($type) {

        $type = DB::table('type')
                ->where('type', '=', $type)
                ->first();  
        $stationSells = DB::table('stationSell')
                            ->where('type_id', '=', $type->id)
                            ->get(); 
        return response()->json($stationSells);        
    }
}
?>