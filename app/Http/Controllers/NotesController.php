<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Log;

use App\Models\Note;

/**
 * Peform all the operations related to notes
 */
class NotesController extends Controller {

    /**
     * @return json Returns all notes
     */
    public function index() {
        Log::info(get_class($this) . '::index');
        return response()->json(['notes' => Note::all()]);
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


    public function notesCount(Request $request) {
      Log::info(get_class($this) . '::notesCount');
      $number = count(Note::where('public', '=', 1)->get());
      return response()->json($number);
    }
}
