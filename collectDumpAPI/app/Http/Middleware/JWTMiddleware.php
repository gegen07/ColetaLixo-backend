<?php
namespace App\Http\Middleware;
use Closure;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware {

    public function handle(Request $request, Closure $next, $guard = null)
    {
        $token = $request->header('Authorization');

        if(!$token) {
            // Unauthorized response if token not there
            return response()->json([
                'error' => 'Token not provided.'
            ], 401);
        }
        try {
            $credentials = JWTAuth::parseToken($token, env('JWT_SECRET'), ['HS256'])->authenticate();
        } catch(TokenExpiredException $e) {
            return response()->json([
                'error' => 'Provided token is expired.'
            ], $e->getStatusCode());
        } catch(JWTException $e) {
            return response()->json([
                'error' => 'An error while decoding token.'
            ], $e->getStatusCode());
        }
        $user = User::find($credentials);
        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;
        return $next($request);
    }
}
