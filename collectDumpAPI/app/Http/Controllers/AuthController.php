<?php
namespace App\Http\Controllers;
use Validator;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Firebase\JWT\ExpiredException;
use Illuminate\Support\Facades\Hash;
use Laravel\Lumen\Routing\Controller as BaseController;
class AuthController extends BaseController {
    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    private $request;
    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request) {
        $this->request = $request;
    }
    /**
     * Create a new token.
     * 
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user) {
        $payload = [
            'iss' => "collect", // Issuer of the token
            'sub' => $user->id, // Subject of the token
            'iat' => time(), // Time when JWT was issued. 
            'exp' => time() + 60*60 // Expiration time
        ];
        
        // As you can see we are passing `JWT_SECRET` as the second parameter that will 
        // be used to decode the token in the future.
        return JWT::encode($payload, env('JWT_SECRET'));
    } 
    /**
     * Authenticate a user and return the token if the provided credentials are correct.
     * 
     * @param  \App\User   $user 
     * @return mixed
     */
    public function authenticate(Request $request) {
        $this->validate($this->request, [
          'email' => 'required|email|max:100',
          'password' => 'required|max:45',
        ]);
        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();
        if (!$user) {
          // probably have some sort of helpers or whatever
          // to make sure that you have the same response format for
          // differents kind of responses. But let's return the 
          // below respose for now.
          return response()->json([
              'error' => 'Email does not exist.'
          ], 400);
        }
        // Verify the password and generate the token
        if (Hash::check($this->request->input('password'), $user->password)) {
          return response()->json([
              'token' => $this->jwt($user)
          ], 200);
        }
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

      return response()->json(['success'=> true, 
                               'message'=> 'Thanks for signing up!']);
    }

    public function logout(Request $request) {
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