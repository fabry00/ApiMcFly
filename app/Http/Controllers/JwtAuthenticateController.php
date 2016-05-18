<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;

/**
 * WARNING
 * to enable JWT authentication modify app\Http\Kernel.php:
 * comment out:
 * \App\Http\Middleware\VerifyCsrfToken::class
 */
class JwtAuthenticateController extends Controller {

    /**
     * this is for our protected route, which just lists all users
     * @return type
     */
    public function index() {
        Log::info(get_class($this) . '::index');
        return response()->json(['auth' => Auth::user(), 'users' => User::all()]);
    }

    /**
     * The authenticate() method uses the JWTAuth's attempt() method to create
     * a token for the user
     * @param Request $request
     * @return type
     */
    public function authenticate(Request $request) {
        Log::info(get_class($this) . '::authenticate');
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /**
     * Return the authenticated user
     *
     * @return Response
     */
    public function getAuthenticatedUser() {
        Log::info(get_class($this) . '::getAuthenticatedUser');
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        // the token is valid and we have found the user via the sub claim
        return response()->json(compact('user'));
    }

    protected function getUserFromToken()
    {
      Log::info(get_class($this) . '::getUserFromToken');
      $user = null;
      try {
          if (!$user = JWTAuth::parseToken()->authenticate()) {
              return $user;
          }
      } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
      } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
      } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
      }
      return $user;
    }



}
