<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'text', 'public',
    ];
    
    /**
     * Get the user that owns the note.
     */
    public function post()
    {
        return $this->belongsTo('App\Models\USer');
    }
}
