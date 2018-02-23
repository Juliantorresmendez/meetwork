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
		    return response()->api($SiteReviews->getReviewsBySite($SiteReviews->site_id),true);
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