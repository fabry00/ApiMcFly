<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\JwtAuthenticateController;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;

use App\Models\Note;
use App\Models\User;

/**
 * Peform all the operations related to notes
 */
class UserController extends JwtAuthenticateController {

    /**
    * @return json all public notes
    */
    public function userNotes(Request $request){
        Log::info(get_class($this) . '::userNotes');
        $loggedUser = $this->getUserFromToken();
        $notes = User::find($loggedUser["id"])->notes()
                  ->orderBy("created_at", "desc")
                  ->get();
        return response()->json($notes);
    }




    public function createRole(Request $request) {
        Log::info(get_class($this) . '::createRole');
        // TODO do some protective checks before saving
        $role = new Role();
        $role->name = $request->input('name');
        $role->save();

        return response()->json("created");
    }

    public function createPermission(Request $request) {
        Log::info(get_class($this) . '::createPermission');
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
        Log::info(get_class($this) . '::assignRole');
        $user = User::where('email', '=', $request->input('email'))->first();

        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return response()->json("created");
    }

    public function attachPermission(Request $request) {
        Log::info(get_class($this) . '::attachPermission');
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('name'))->first();
        $role->attachPermission($permission);

        return response()->json("created");
    }
}
