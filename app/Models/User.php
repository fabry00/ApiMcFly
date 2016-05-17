<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

use App\Models\Note;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{

    use Authenticatable, CanResetPassword;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the notes for the user.
     */
    public function notes()
    {
        return $this->hasMany('App\Models\Note');
    }

    public function favorite_notes()
    {
        return $this->belongsToMany('App\Models\Note', 'favorite_notes');
    }

}
