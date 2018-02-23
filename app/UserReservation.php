<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReservation extends Model
{
    public $primaryKey = 'id';
    protected $table = 'user_reservation';

 public function site()
    {
        return $this->hasOne("App\Site","id","site_id");
    }
    public function getUserRegistration($user_id,$site_id,$date,$time){
    	return $this
    	->where("site_id",$site_id)
    	->where("user_id",$user_id)
    	->where("date_reservation",$date)
    	->where("time",$time)
    	->get();
    }
  
   
}
