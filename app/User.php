<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

      public function reservations()
    {
        return $this->hasMany("App\UserReservation");
    }

    public function getuser($id){
        return $this->where("id",$id)
        ->select("name","id")
        ->with(['reservations' => function ($query){
            $query->select("id","site_id","user_id");
            $query->with(['site' => function ($query){
                $query->select("id","name","address","lat","lon");
                $query->with('services');
            }]);
        }])
        ->first();
    }

    public function getUserByActivation($code,$email){
        return $this
        ->where("email",$email)
        ->where("confirmation_code",$code)
        ->first();
    }
}
