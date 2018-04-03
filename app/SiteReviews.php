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
                ->where("status_id",1)
   		->with("user")
   		->get();
   }
   
   
      
   public function getReviewBySite($id){
   		return $this->where("id",$id)
   		->orderBy("created_at","desc")
   		->with("user")
   		->first();
   }


   public function getReviewsAvg($site){
   		return $this
   		->where("site_id",$site)
                                        ->where("status_id",1)

   		->avg("rating");
   }
}



