<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\JwtAuthenticateController;
use Log;

use App\Models\Note;
use App\Models\User;

/**
 * Peform all the operations related to notes
 */
class NotesController extends JwtAuthenticateController {

    /**
     * @return json Returns all notes
     */
    public function index() {
        Log::info(get_class($this) . '::index');
        return response()->json(['notes' => Note::all()]);
    }

    public function createNote(Request $request){
        Log::info(get_class($this) . '::createNote ');
        $params = $request->only('text', 'public','favorite');
        if(empty($params["text"])){
            return response()->json(array("message"=>"Unable to add note, text not found"), 400);
        }
        $loggedUser = $this->getUserFromToken();
        $note = new Note();
        $note->text = $params["text"];
        $note->user_id = $loggedUser["id"];
        $note->public = $params["public"];

        $note->save();
        if($params["favorite"]){
          $loggedUser->favorite_notes()->attach($note->id);
        }
        return response()->json();
    }

    public function deleteNote(Request $request){
      Log::info(get_class($this) . '::deleteNote ');
      $params = $request->only('id');
      $loggedUser = $this->getUserFromToken();
      $note = User::find($loggedUser["id"])->notes()
                ->where("notes.id",$params["id"])->first();
      if($note != null){
        Log::info(get_class($this) . '::deleteNote deleting note '.$note." note to search: ".$params["id"]);
        $note->delete();
        return response()->json();
      }

      Log::error(get_class($this) . '::deleteNopteuser isn\'t the note owner --> unable to delete');
      return response()->json(array("message"=>"Unable to delete note, note not found"), 400);
    }

    /**
    * @return json all public notes
    */
    public function publicNotes(Request $request){
        Log::info(get_class($this) . '::publicNotes');
        $publicNotes = Note::where('public', '=', 1)
                                    ->with(array('user' => function($query){
                                          // You must always select the foreign
                                          // key and primary key of the relation.
                                          $query->select('name', 'id');
                                      }))
                                    ->orderBy("created_at", "desc")
                                  //  ->limit(10) // TODO create the pagination
                                    ->get();

        return response()->json($publicNotes);
    }

    /**
    * @return all the Public notes and if the user logged set them as favorite
    */
    public function publicNotesWithUserFav(Request $request){
        Log::info(get_class($this) . '::publicNotesWithUserFav');
        $loggedUser = $this->getUserFromToken();
        $publicNotes = [];
        $userFavnotes = User::find($loggedUser["id"])->favorite_notes()->get();
        $notes = Note::where('public', '=', 1)
                                    ->with(array('user' => function($query){
                                          $query->select('name', 'id');
                                      }))
                                    ->orderBy("created_at", "desc")
                                    ->get();

        foreach ($notes as $note) {
          foreach ($userFavnotes as  $favNote) {
            if($favNote["id"] == $note["id"] ){
              $note["pivot"] = array(
                "user_id" => $loggedUser["id"],
                "note_id" => $favNote["id"]
              );
              break;
            }
          }
          $publicNotes[] = $note;
        }
        return response()->json($publicNotes);
    }

    public function notesCount(Request $request) {
      Log::info(get_class($this) . '::notesCount');
      $number = count(Note::where('public', '=', 1)->get());
      return response()->json($number);
    }
}
