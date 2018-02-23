<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use File;
use App\Site;
use App\Service;
use App\Space;
use App\UserReservation;
use Auth;

class SiteController extends Controller
{

	public function searchSites(Request $request){
		$site= new Site();
		return $site->searchSites($request);
	}


	public function getFilters(){
		$Service= Service::select("id","name")->get();
		$Space= Space::select("id","name")->get();
		return ["services"=>$Service,"spaces"=>$Space];
	}

	public function getIndividual($id){
		return Site::with("services")
				->with("spaces")
				->with("images")
				->with("logo")
				->with(array('reviews'=>function($query){
        	$query->with('user');
        	$query->orderBy('created_at',"desc");
    	}))->find($id);
	}

	public function postReserv(Request $request, $id){
		if(Auth::check()){
			$userReservation= new UserReservation();
			$getUserRegistration=$userReservation->getUserRegistration(Auth::id(),$id,$request->get("time"),$request->get("hour"));
			if($getUserRegistration->count()==0){
				$userReservation->number_person=$request->get("numberOfPersons");
				$userReservation->date_reservation=$request->get("time");
				$userReservation->time=$request->get("hour");
				$userReservation->site_id=$id;
				$userReservation->user_id=Auth::id();
				$userReservation->save();
			}else{
			    return response()->api("Ya reservaste este lugar para esta fecha y hora",false);
			}
			
		    return response()->api("Se ha reservado con éxito",true);


		}else{
		    return response()->api("No estas logueado ingresa a la pantalla de ingreso",false);
		}
		
	}

}