<?php
 
namespace App\Http\Controllers;
 
use App\Models\Type;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Transformers\TypeTransformer;
use Illuminate\Support\Facades\Validator;
 
class TypeController extends Controller{

	use Helpers;

	public function create(Request $request){
		$payload = $request->only('type');
		$rules = [
			'type' => ['required', 'max:80', 'unique:type']
		];
		$validator = Validator::make($payload, $rules);

		if (!$validator->fails()) {
			$type = Type::create($request->all());
			return $this->response->item($type, new TypeTransformer);
		} else {
			throw new \Dingo\Api\Exception\StoreResourceFailedException('Could not create new type.', $validator->errors());
		} 
	}
 
	public function update(Request $request, $id){
		$payload = $request->only('type');
		$rules = [
			'type' => ['required', 'max:80', 'unique:type']
		];
		$validator = Validator::make($payload, $rules);

		if (!$validator->fails()) {
			try {
				$type = Type::findOrFail($id);
				$type->type = $request->input('type');
				$type->save();
				return $this->response->item($type, new TypeTransformer)->setStatusCode(200);
			} catch (\Exception $e) {
				throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found type');
			}
		} else {
			throw new \Dingo\Api\Exception\UpdateResourceFailedException('Could not update type.', $validator->errors());
		}
	}  

	public function delete($id) {
		$type = Type::destroy($id);
		if($type) {
			return response(
				[
					'status' => $type ? "success" : "Not found.",
				], $statusCode ?? 201
			);
		} else {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found type');
		}

		
	}

	public function show($id)
    {
        try {
			$type = Type::findOrFail($id);
			return $this->response->item($type, new TypeTransformer)->setStatusCode(200);
        } catch (\Exception $e) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found type');
        }
    }

	public function index(){

    	$type  = Type::paginate(12);
		return $this->response->paginator($type, new TypeTransformer); 
	}
}
?>