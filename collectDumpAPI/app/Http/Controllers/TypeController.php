<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Models\Role;
use App\Http\Controllers\Controller;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
use App\Transformers\TypeTransformer;
use Illuminate\Support\Facades\Validator;

class TypeController extends Controller{

	use Helpers;

  public function __construct()
  {
      $this->middleware('auth:api');
  }

	public function create(Request $request){
		$request->user()->authorizeRoles(['station', 'company']);

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
		$request->user()->authorizeRoles(['station', 'company']);

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
		$request->user()->authorizeRoles(['station', 'company']);

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

	public function show($id) {
		$request->user()->authorizeRoles(['station', 'company']);

		try {
			$type = Type::findOrFail($id);
			return $this->response->item($type, new TypeTransformer)->setStatusCode(200);
    } catch (\Exception $e) {
			throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Could not found type');
    }
  }

	public function index(){
		$request->user()->authorizeRoles(['station', 'company']);

    $type  = Type::paginate(12);
		return $this->response->paginator($type, new TypeTransformer);
	}
}
?>