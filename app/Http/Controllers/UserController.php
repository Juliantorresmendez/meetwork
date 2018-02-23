<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use File;
use App\Site;
use App\Service;
use App\Space;
use Auth;

class UserController extends Controller
{

	 public function profile($id){
	 	$user= new user();
	 	$getuser=$user->getuser($id);
	 	return $getuser;
	 }

	 public function profileIndividual(){
	 	if(Auth::check()){
	 		$user= new user();
		 	$getuser=$user->getuser(Auth::id());
		 	return $getuser;
	 	}else{
		    return response()->api("Hay un error Al traer el perfil",false);

	 	}
 		
	 }
}