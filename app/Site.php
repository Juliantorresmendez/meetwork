<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use DB;
use Geographical;

class Site extends Model
{
    const LATITUDE  = 'lat';
    const LONGITUDE = 'lng';
    
    
    
    protected static $kilometers = true;

    public $primaryKey = 'id';

 	public function services()
    {
        return $this->belongsToMany('App\Service');
    }
    
       public function searchUserSites($request,$user_id){
    	$query = Site::query();
      



    if($request->get("services")){
        
        
        $servicesArray= explode(",", $request->get("services"));
        $query->whereHas('services', function($query) use ($servicesArray) {
            $query->whereIn("services.id",$servicesArray);
        });
    }

    if($request->get("selectedSpace")){
        $servicesArray= explode(",", $request->get("selectedSpace"));
        $query->whereHas('spaces', function($query) use ($servicesArray) {
            $query->whereIn("spaces.id",$servicesArray);
        });
    }
    
     
    
    if($request->get("siteSearch")){
            $query->where("name","LIKE","%".$request->get("siteSearch")."%");

    }

        

    if($request->get("status_id")){
            $query->where("status_id",$request->get("status_id"));
    }else{
            $query->where("status_id",2);
 
    }
    $query->where("admin",$user_id);
    
    $query->with("services");
    $query->with("spaces");
    $query->with("images");

   // $query->with("logo");
    $query->with("principal");


   return $query->paginate(21);

    }
    
    
    public function searchAdminSites($request){
    	$query = Site::query();
      



    if($request->get("services")){
        
        
        $servicesArray= explode(",", $request->get("services"));
        $query->whereHas('services', function($query) use ($servicesArray) {
            $query->whereIn("services.id",$servicesArray);
        });
    }

    if($request->get("selectedSpace")){
        $servicesArray= explode(",", $request->get("selectedSpace"));
        $query->whereHas('spaces', function($query) use ($servicesArray) {
            $query->whereIn("spaces.id",$servicesArray);
        });
    }
    
     
    
    if($request->get("siteSearch")){
            $query->where("name","LIKE","%".$request->get("siteSearch")."%");

    }

        

    if($request->get("status_id")){
            $query->where("status_id",$request->get("status_id"));

    }

    
    $query->with("services");
    $query->with("spaces");
    $query->with("images");

   // $query->with("logo");
    $query->with("principal");


   return $query->paginate(21);

    }
    

    
    
    public function spaces()
    {
    return $this->belongsToMany('App\Space');
    }


    public function reviews()
    {
    return $this->hasMany('App\SiteReviews')->where("status_id",1);
    }

    public function images()
    {
    return $this->hasMany('App\SiteImage')->where("type",1)->where("status_id",1);
    }
        public function principal()
    {
    return $this->hasMany('App\SiteImage')->where("type",1)->take(1);
    }

    /*public function logo()
    {
    return $this->hasMany('App\SiteImage')->where("type",3)->where("status_id",1);
    }*/
    public function city()
    {
    return $this->hasOne('App\City',"id","city");
    }
    
    public function user()
    {
        return $this->hasOne('App\User',"id","admin");
    }

    public function searchSites($request){
      
    	$query = Site::query();


  $withgeo=true;
    if($request->get("services")){
        $servicesArray= explode(",", $request->get("services"));
        $query->whereHas('services', function($query) use ($servicesArray) {
            $query->whereIn("services.id",$servicesArray);
        });
        $withgeo=false;
        
    }

    if($request->get("selectedSpace")){
        $servicesArray= explode(",", $request->get("selectedSpace"));
        $query->whereHas('spaces', function($query) use ($servicesArray) {
            $query->whereIn("spaces.id",$servicesArray);
        });
                $withgeo=false;

    }
    
    if($withgeo){
        if($request->get("lat")!=0 && $request->get("lon")!=0){

     $this->scopeIsWithinMaxDistance($query,array("lat"=>$request->get("lat"),"lon"=>$request->get("lon")),1);
    }
    }
          
    


    
    $query->with("services");
    $query->with("spaces");
    $query->with("images");
    $query->whereHas("images");
    //$query->with("logo");
    $query->with("principal");
    $query->where("status_id",2);

   return $query->paginate(20);

    }
    
    public function getSiteEmail($id){
        return $this->where("admin",$id)->first();
    }


public function scopeIsWithinMaxDistance($query, $coordinates, $radius = 5) {

    $haversine = "(3959 * acos(cos(radians(" . $coordinates['lat'] . ")) 
                    * cos(radians(`lat`)) 
                    * cos(radians(`lon`) 
                    - radians(" . $coordinates['lon'] . ")) 
                    + sin(radians(" . $coordinates['lat'] . ")) 
                    * sin(radians(`lat`))))";

    return $query->select('*')
                 ->selectRaw("{$haversine} AS distance")
                 ->whereRaw("{$haversine} < ?", [$radius]);
}
    

}
