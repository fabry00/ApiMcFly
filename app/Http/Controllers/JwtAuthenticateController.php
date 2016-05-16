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
        return response()->json(['auth' => Auth::user(), 'users' => User::all()]);
    }

    /**
     * The authenticate() method uses the JWTAuth's attempt() method to create 
     * a token for the user
     * @param Request $request
     * @return type
     */
    public function authenticate(Request $request) {
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

    public function createRole(Request $request) {
        Log::info('createRole');
        // TODO do some protective checks before saving
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();

        return response()->json("created");
    }

    public function createPermission(Request $request) {
        Log::info('createPermission');
        // TODO do some protective checks before saving
        $viewUsers = new Permission();
        $viewUsers->name = $request->input('name');
        $viewUsers->save();

        return response()->json("created");
    }

    /**
     * the assignRole is responsible for assigning a given role to a user
     * @param Request $request
     */
    public function assignRole(Request $request) {
        Log::info('assignRole');
        $user = User::where('email', '=', $request->input('email'))->first();

        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return response()->json("created");
    }

    public function attachPermission(Request $request) {
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json("created");       
    }

}
