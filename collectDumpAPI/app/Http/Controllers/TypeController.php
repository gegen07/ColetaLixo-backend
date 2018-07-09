<?php
 
namespace App\Http\Controllers;
 
use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class TypeController extends Controller{

	public function create(Request $request){
 
    	$type = Type::create($request->all());
 
    	return response()->json($type);
 
	}
 
	public function update(Request $request, $id){

    	$type = Type::find($id);
    	$type->type = $request->input('type');
    	$type->save();
 
    	return response()->json($type);
	}  

	public function delete($id){
    	$type  = Type::find($id);
    	$type->delete();
 
    	return response()->json('Removed successfully.');
	}

	public function index(){
 
    	$type  = Type::all();
 
    	return response()->json($type);
 
	}
}
?>