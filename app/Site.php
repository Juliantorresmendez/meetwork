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

    public function spaces()
    {
    return $this->belongsToMany('App\Space');
    }


    public function reviews()
    {
    return $this->hasMany('App\SiteReviews');
    }

    public function images()
    {
    return $this->hasMany('App\SiteImage')->where("type",1);
    }
        public function principal()
    {
    return $this->hasMany('App\SiteImage')->where("type",1)->take(1);
    }

    public function logo()
    {
    return $this->hasMany('App\SiteImage')->where("type",3);
    }


    public function searchSites($request){
    	$query = Site::query();
      


    if($request->get("lat")!=0 && $request->get("lon")!=0){
        $this->scopeIsWithinMaxDistance($query,array("lat"=>$request->get("lat"),"lon"=>$request->get("lon")),1);
    }

    if($request->has("services")){
        $servicesArray= explode(",", $request->get("services"));
        $query->whereHas('services', function($query) use ($servicesArray) {
            $query->whereIn("services.id",$servicesArray);
        });
    }

    if($request->has("spaces")){
        $servicesArray= explode(",", $request->get("spaces"));
        $query->whereHas('spaces', function($query) use ($servicesArray) {
            $query->whereIn("spaces.id",$servicesArray);
        });
    }

    
    $query->with("services");
    $query->with("spaces");
    $query->with("images");
    $query->with("logo");
    $query->with("principal");


   return $query->paginate(20);

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
