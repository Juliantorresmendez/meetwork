<?php

namespace App;

use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

  /*
class User extends Model implements AuthenticatableContract, CanResetPasswordContract
{
use Authenticatable, CanResetPassword, HasRole;
*/ 
use Kodeine\Acl\Traits\HasRole;

class User extends Model implements AuthenticatableContract
{
    use Authenticatable, HasRole;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','cel','ip','position','social',
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
    
    public function searchusers($name){
        
        return $this->where("name","LIKE","%".$name."%")->get();
    }
    
    public function getByRememberPassword($remember){
        return $this->where("remember_password",$remember)->first();
    }

    public function getuser($id){
        return $this->where("id",$id)
        ->select("name","id","avatar")
        ->with(['reservations' => function ($query){
            $query->select("id","site_id","user_id");
            $query->with(['site' => function ($query){
                $query->select("id","name","address","lat","lon");
                $query->with('services');
                $query->with('images');
               // $query->with('logo');
                
            }]);
        }])
        ->first();
    }
    
    
    
    public function getuserProfile($id){
        return $this->where("id",$id)
        ->select("name","lastname","id","email","country","cel","profesion","bio","avatar")
        ->with(['reservations' => function ($query){
            $query->select("id","site_id","user_id","status_id");
            $query->with(['site' => function ($query){
                $query->select("id","name","address","lat","lon");
                $query->with('services');
                  $query->with('images');
               // $query->with('logo');
            }]);
            $query->where("status_id",1); 
        }])
        ->first();
    }
    public function getUserByEmail($email){
        return $this->where("email",$email)->first();
    }
 
    
    public function getUserByEmailSocial($email,$social){
        return $this
                ->where("social",$social)
                ->where("email",$email)
                ->first();
    }
    public function getUserByActivation($code,$email){
        return $this
        ->where("email",$email)
        ->where("confirmation_code",$code)
        ->first();
    }
} 
