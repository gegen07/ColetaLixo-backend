<?php
 
namespace App\Http\Controllers;
 
use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
 
class TypeController extends Controller{

	public function create(Request $request){
 
			$type = Type::create($request->all());
			
			$statusCode = $type ? 200 : 422;
 
			return response()->json(
				[
					'data' => $type,
					'statusCode' => $statusCode = $type ? 200 : 422
				], $statusCode);
 
	}
 
	public function update(Request $request, $id){

			$this->validate($request, [
					'type' => 'required'
			]);

			try {
				$type = Type::findOrFail($id);
				$type->type = $request->input('type');
				$type->save();
			} catch(\Exception $e) {
				$type = null;
				$statusCode = 404;
			}
 
			return response()
				->json(
					[
						'data' => $type,
						'statusCode' => $statusCode = $type ? 'success' : 'Not Found'
					], $statusCode);
	}  

	public function delete($id){
			try {
					$type = Type::findOrFail($id);
					$type->delete();
			} catch(\Exception $e) {
					$type = null;
					$statusCode = 404;
			}
			return response(
					[
							"data" => $type,
							"status" => $type ? "success" : "Not found."
					], $statusCode ?? 200
			);
 
    	return response()->json(['data'=>$type], $statusCode);
	}

	public function show($id)
    {
        try {
            $type = Type::findOrFail($id);
        } catch (\Exception $e) {
            $type = null;
            $statusCode = 404;
        }
        return response(
            [
                'data' => $type,
                'status' => $type ? "success" : "Not found.",
            ], $statusCode ?? 201
        );
    }

	public function index(){

    	$type  = Type::all();
 
    	return response()->json(['data' => $type]);
 
	}
}
?>