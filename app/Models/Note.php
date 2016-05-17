<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Note extends Model
{
    protected $fillable = [
        'text', 'public',
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }


    public function users_favorite()
    {
        return $this->belongsToMany('App\Models\User','favorite_notes');
    }
}
