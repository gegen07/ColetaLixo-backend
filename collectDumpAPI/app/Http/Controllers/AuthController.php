<?php
namespace App\Http\Controllers;

use Validator;
use App\Models\Role;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Transformers\UserTransformer;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Dingo\Api\Exception\ValidationHttpException;

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
        $this->middleware('auth:api', ['except' => ['authenticate', 'register', 'sendEmail']]);
    }


    public function validate(
            Request $request,
            array $rules,
            array $messages = [],
            array $customAttributes = [])
    {
        $validator = $this->getValidationFactory()
            ->make(
                $request->all(),
                $rules, $messages,
                $customAttributes
            );
        if ($validator->fails()) {
            throw new ValidationHttpException(
                $validator->errors()
            );
        }
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

    public function guard()
    {
        return Auth::guard('api');
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

        try {
            // attempt to verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['message' => 'Email or password is wrong.'], 404);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['message' => 'Failed to login, please try again.'], 500);
        }

        $user = User::where('email',$request->input('email'))->with('roles')->first();

        return response()->json([
          'data' =>
          ['token' => static::respondWithToken($token),
           'user' => UserTransformer::transform($user)]
        ], 200);
    }

    public function register(Request $request)
    {
      $credentials = $request->only('name', 'email', 'password', 'address', 'telephone', 'role');

      $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email|max:255|unique:users',
        'password' => 'required|max:20',
        'address' => 'required',
        'telephone' => 'required',
        'role' => 'required'
      ];

      $validator = Validator::make($credentials, $rules);
      if($validator->fails()) {
        return response()->json(['message'=> $validator->messages()], 400);
      }

      $name = $request->name;
      $email = $request->email;
      $password = $request->password;
      $address = $request->address;
      $telephone = $request->telephone;
      $role = $request->role;

      $user = User::create(['name' => $name,
                            'email' => $email,
                            'password' => Hash::make($password),
                            'address' => $address,
                            'telephone' => $telephone
                            ]);
      $user->roles()->attach(Role::where('name', $role)->first());

      return response()->json(['message'=> 'Thanks for signing up!'], 200);
    }

    public function me()
    {
        return response()->json(UserTransformer::transform($this->guard()->user()));
    }

    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    public function logout() {


        try {
            $this->guard()->logout();
            return response()->json(['message'=> "You have successfully logged out."], 200);
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['message' => 'Failed to logout, please try again.'], 500);
        }
    }

    public function station(Request $request, $id) {
        $request->user()->authorizeRoles(['station','company']);

        $users = User::whereHas('roles', function($query){
            $query->where('name', 'station');
        });

        $user = $users->findOrFail($id);

        return response()->json(UserTransformer::transform($user));
    }

    public function company(Request $request, $id) {
        $request->user()->authorizeRoles(['station','company']);

        $users = User::whereHas('roles', function($query){
            $query->where('name', 'company');
        });

        $user = $users->findOrFail($id);

        return response()->json(UserTransformer::transform($user));
    }

    public function sendEmail () {

        $data = array(
            'name' => "Learning Laravel",
        );

        Mail::send('emails.welcome', $data, function ($message) {

            $message->from('gegenbarcelos@gmail.com', 'Germano Barcelos');

            $message->to('gegeburger95@gmail.com')->subject('Learning Laravel test email');

        });

        return "Your email has been sent successfully";
    }

}
