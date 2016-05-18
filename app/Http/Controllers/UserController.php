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


    public function index() {
      // THIS IS JUST FOR DEMO --> THIS MUST BE UNDER AUTH CONTROL
      return response()->json(['users' => User::with('roles.perms')->get()]);
    }

    /**
    * @return json all user notes
    */
    public function userNotes(Request $request){
        Log::info(get_class($this) . '::userNotes');
        $loggedUser = $this->getUserFromToken();

        // TODO improve this function
        $userFavnotes = User::find($loggedUser["id"])->favorite_notes()->get();
        $notes = User::find($loggedUser["id"])->notes()
                  ->orderBy("created_at", "desc")
                  ->get();

        $allUserNotes = [];
        foreach ($notes as $note) {
          //if($note["id"])
          foreach ($userFavnotes as  $favNote) {
            if($favNote["id"] == $note["id"] ){
              $note["pivot"] = array(
                "user_id" => $loggedUser["id"],
                "note_id" => $favNote["id"]
              );
              break;
            }
          }
          $allUserNotes[] = $note;
        }
        return response()->json($allUserNotes);
    }

    /**
    * @return json all user favorite notes
    */
    public function userFavNotes(Request $request){
        Log::info(get_class($this) . '::userFavNotes');
        $loggedUser = $this->getUserFromToken();
        $notes = User::find($loggedUser["id"])->favorite_notes()
                    ->with(array('user' => function($query){
                      // You must always select the foreign
                      // key and primary key of the relation.
                      $query->select('name', 'id');
                    }))
                  ->orderBy("created_at", "desc")
                  ->get();
        return response()->json($notes);
    }

    /**
    * @return json
    */
    public function setFavorite(Request $request){
        Log::info(get_class($this) . '::setFavorite');
        $loggedUser = $this->getUserFromToken();
        $params = $request->only('noteid', 'fav');
        if($this->isPublic($params["noteid"]) || $this->userOwnNote($params["noteid"],$loggedUser) ){
          // Set Favorite
          if($params['fav']){
              User::find($loggedUser["id"])->favorite_notes()->attach($params["noteid"]);
          }else{
              User::find($loggedUser["id"])->favorite_notes()->detach($params["noteid"]);
          }
          return response()->json();
        }

        Log::error(get_class($this) . '::setFavorite the not is not public '.
                     '. or user isn\'t the note owner --> unable to set as favorite');
        return response()->json(array("error"=>"Unable to set note as favorite"), 400);
    }

    public function publish(Request $request){
        Log::info(get_class($this) . '::publish');
        $loggedUser = $this->getUserFromToken();
        $params = $request->only('noteid', 'publish');
        if($this->userOwnNote($params["noteid"],$loggedUser) ){
          // Set Favorite
          $publish = $params['publish']?1:0;
          $note = Note::find($params["noteid"]);
          $note->public = $publish;
          $note->save();
          return response()->json();
        }

        Log::error(get_class($this) . '::setFavorite the not is not public '.
                     '. or user isn\'t the note owner --> unable to set as favorite');
        return response()->json(array("error"=>"Unable to set note as favorite"), 400);
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

    protected function isPublic($noteId){
        Log::info(get_class($this) . '::isPublic noteid:'.$noteId);
        $note = Note::where("id",$noteId)->where('public', '=', 1)->first();
        return $note != null;
    }

    protected function userOwnNote($noteId, $user){
      Log::info(get_class($this) . '::userOwnNote noteid:'.$noteId);
      $note = User::find($user["id"])->notes()
                ->where("notes.id",$noteId)
                ->get();
      return $note != null;
    }
}
