<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SiteReviews extends Model
{
    public $primaryKey = 'id';
    protected $table = 'site_reviews';

   public function user()
    {
    return $this->hasOne('App\User',"id","user_id");
    }
   
   public function getReviewsBySite($site){
   		return $this->where("site_id",$site)
   		->orderBy("created_at","desc")
   		->with("user")
   		->take(10)
   		->get();
   }


   public function getReviewsAvg($site){
   		return $this
   		->where("site_id",$site)
   		->avg("rating");
   }
}



