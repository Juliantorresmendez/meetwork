<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
class UserReservation extends Model
{
    public $primaryKey = 'id';
    protected $table = 'user_reservation';

 public function site()
    {
        return $this->hasOne("App\Site","id","site_id");
    }
    
    
     public function user()
    {
        return $this->hasOne("App\User","id","user_id");
    }
    
     public function siteSimple() {
        return $this->hasMany("App\Site", "id", "site_id");
    }

    public function userSimple() {
        return $this->hasMany("App\User", "id", "user_id");
    }
    
    public function getUserRegistration($user_id,$site_id,$date,$time){
    	return $this
    	->where("site_id",$site_id)
    	->where("user_id",$user_id)
    	->where("date_reservation",$date)
    	->where("time",$time)
    	->get();
    }
  
    public function getSimpleReservation(){
        
    }
    public function getPastSiteReservation($site_id){
        return $this
                ->where("site_id",$site_id)
                ->where("user_reservation.status_id",3)
                ->join("sites","user_reservation.site_id","sites.id")
                ->join("users","user_reservation.user_id","users.id")
                ->select("number_person","date_reservation","time","sites.name as site_name","users.name as user_name","users.id as user_id","sites.id as site_id")

                ->get();
    }
    
    
    
      
    public function getCurrentSiteReservation($site_id){
        return $this
                ->where("site_id",$site_id)
                ->where("user_reservation.status_id","<>",3)

                ->join("sites","user_reservation.site_id","sites.id")
                ->join("users","user_reservation.user_id","users.id")
                ->select("user_reservation.id","user_reservation.status_id","number_person","date_reservation","time","sites.name as site_name","users.name as user_name","users.id as user_id","sites.id as site_id")
                ->whereDate('date_reservation', DB::raw('CURDATE()'))
                ->get();
    }
    
      
    public function getFutureSiteReservation($site_id){
        return $this
                ->where("site_id",$site_id)
                                ->where("user_reservation.status_id","<>",3)

                   ->join("sites","user_reservation.site_id","sites.id")
                 ->join("users","user_reservation.user_id","users.id")
                ->select("user_reservation.id","user_reservation.status_id","number_person","date_reservation","time","sites.name as site_name","users.name as user_name","users.id as user_id","sites.id as site_id")

                ->whereDate('date_reservation',">", DB::raw('CURDATE()'))
                ->get();
    }
    
        public function getPedingSiteReservation($site_id){
        return $this
                ->where("site_id",$site_id)
                    ->join("sites","user_reservation.site_id","sites.id")
                ->join("users","user_reservation.user_id","users.id")
                ->select("number_person","date_reservation","time","sites.name as site_name","users.name as user_name","users.id as user_id","sites.id as site_id")

                ->where("user_reservation.status_id",1)
                ->get();
    }
    
   
}
