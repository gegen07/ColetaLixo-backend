<?php
namespace App\Http\Controllers;

use Validator;
use App\Models\Role;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
class AuthController extends BaseController {

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['authenticate', 'register']]);
    }


    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }

    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     *
     * @param  \App\User   $user
     * @return mixed
     */
    public function authenticate(Request $request) {
        $credentials = $request->only('email', 'password');
        $this->validate($request, [
          'email' => 'required|email|max:100',
          'password' => 'required|max:45',
        ]);

        // Verify the password and generate the token
        if (!$token = JWTAuth::attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['success' => true, 'data'=> [ 'token' => $token ]], 200);
        // Bad Request response
        return response()->json([
          'error' => 'Email or password is wrong.'
        ], 400);
    }

    public function register(Request $request)
    {
      $credentials = $request->only('name', 'email', 'password', 'address', 'telephone');

      $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|max:20',
        'address' => 'required',
        'telephone' => 'required'
      ];

      $validator = Validator::make($credentials, $rules);
      if($validator->fails()) {
        return response()->json(['success'=> false, 'error'=> $validator->messages()]);
      }

      $name = $request->name;
      $email = $request->email;
      $password = $request->password;
      $address = $request->address;
      $telephone = $request->telephone;

      $user = User::create(['name' => $name,
                            'email' => $email,
                            'password' => Hash::make($password),
                            'address' => $address,
                            'telephone' => $telephone
                            ]);
      $user->roles()->attach(Role::where('name', $request->role)->first());

      return response()->json(['success'=> true,
                               'message'=> 'Thanks for signing up!']);
    }

    public function logout() {
      $this->validate($request, ['token' => 'required']);

        try {
            JWTAuth::invalidate($request->input('token'));
            return response()->json(['success' => true, 'message'=> "You have successfully logged out."]);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['success' => false, 'error' => 'Failed to logout, please try again.'], 500);
        }
    }
}
