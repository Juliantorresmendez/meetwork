<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use File;
use App\Site;
use App\Service;
use App\SiteReviews;
use Auth;
use Mail;

class ReviewController extends Controller
{

	public function postReview(Request $request,$id){
		if(Auth::check()){
			$SiteReviews=  new SiteReviews();
			$SiteReviews->review=$request->get("commentText");
			$SiteReviews->site_id=$id;
			$SiteReviews->user_id=Auth::id();
			$SiteReviews->rating=$request->get("rating");
			$SiteReviews->save();

                        
                        Mail::raw('Hay un nuevo review '.$SiteReviews->review.' con id  <<< '.$SiteReviews->id.'  >>> <<<<< del usuario '.$SiteReviews->user->name.' con id '.$SiteReviews->user->id.' >>>> en el sitio id '.$id, function ($message) use ($id) {
   
                             $message->to("reservas@meetwork.co", "MeetWork")
                                ->subject('hay un nuevo review en el site ' . $id);
                            
                        });
		    return response()->api($SiteReviews->getReviewBySite($SiteReviews->id),true);
		}else{
		    return response()->api("Tienes que estar logueado para poder comentar",false);
		}
		

	}
 
	public function getReviewsCount($site_id){


		$SiteReviews=  new SiteReviews();
		$avg=$SiteReviews->getReviewsAvg($site_id);

		$count=$SiteReviews->getReviewsBySite($site_id);

	    return response()->api(array("avg"=>$avg,"count"=>count($count)),true);

		
	}

}