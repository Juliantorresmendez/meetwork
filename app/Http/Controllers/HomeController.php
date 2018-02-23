<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use File;
use App\Site;
use App\City;
use App\Type;
use App\Service;
use App\Space;
use App\SpaceSite;
use App\SiteImage;
use App\ServiceSite;

use Auth;
use Input;
use Image;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{


	public function logout(){
                Auth::logout();
    }

    public function demoImage(Request $request){



return '<img src="'.Image::url('/uploads/45/0.jpg',300,300,array('crop','grayscale')).'"/>';;


dd();
ini_set("memory_limit",-1);

 

    	    	$folderId=99;

    	    	$sites=Site::find($request->get("id"));
    	    	
    	    	$folderId=$sites->id;
    	    	try {
    	    		
    	    	

    	$files = File::allFiles(public_path('uploads/'.$folderId));
    	if (!file_exists(public_path('thumbs/'.$folderId))) {
    		mkdir(public_path('thumbs/'.$folderId), 0777, true);
		}
foreach ($files as $file)
{
    $url=explode("/", (string)$file);
    $explodeUrl= $url[count($url)-1];
    $nameOfFile=explode(".", $explodeUrl);
    $explodeUrl[1]=strtolower($explodeUrl[1]);

    if(is_numeric($nameOfFile[0])){

 		$full=public_path('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);

 		$thumb=public_path('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);
    	 Image::make($full,array(
			'width' => 800,
			'height' => 500,
		    'crop' => true,

		))->save($thumb);


 /*	 $SiteImage= new SiteImage();
	 	 $SiteImage->url=url('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);
 	 $SiteImage->type=1;
 	 $SiteImage->site_id=$folderId;
  	 $SiteImage->save();*/




    }else{
    	/*
    	$full=public_path('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);
 		$thumb=public_path('/thumbs/'.$folderId.'/logo-small.'.$nameOfFile[1]);
    	 Image::make($full,array(
			'width' => 30,
			'height' => 30,
		    'crop' => true,

		))->save($thumb);

    	 $SiteImage= new SiteImage();
	 	 $SiteImage->url=url('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);
	 	 $SiteImage->type=3;
	 	 $SiteImage->site_id=$folderId;
	  	 $SiteImage->save();


 		$thumb=public_path('/thumbs/'.$folderId.'/logo-medium.'.$nameOfFile[1]);
    	 Image::make($full,array(
			'width' => 76,
			'height' => 76,
		    'crop' => true,

		))->save($thumb);*/


    }
};
}catch (\Exception $e) {
    echo  $e->getMessage();
}
$siguiente=$folderId+1;
	
echo "<script>window.onload = function(e){ 
 setTimeout(function () {
        window.location='http://localhost/Laravel-Vue/public/demoImage?id=$siguiente'

    }, 2000);



}</script>";

    	
    }

public function getUrl($address){
	$ch = curl_init();
	$url='http://maps.google.com/maps/api/geocode/json?address='.urlencode($address).',buenos+aires,ciudad+autonoma+de+buenos+aires&sensor=false';
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $url);
$result = curl_exec($ch);
curl_close($ch);

 
$output= json_decode($result);
if(isset($output->results[0])){
$lat = $output->results[0]->geometry->location->lat;

$long = $output->results[0]->geometry->location->lng;
return ["lat"=>$lat,"lon"=>$long ];
}else{
	dd($output);
}



}

	public function formatDirections(){
		$sites=Site::where("lat","47.99")->get();

		foreach ($sites as $key => $value) {
			$site=Site::find($value->id);

			
	 			$json = $this->getUrl($site->address);

				$site->lat=$json["lat"] ;
				$site->lon=$json["lon"] ;
				$site->save();
			
	       
		}

		return $sites;
	}

	public function formatPlaces(){

	        $json = File::get("places.json");
            $data = json_decode($json,true);

		foreach ($data["data"] as $key => $value) {
						
				

				if(isset($value["attributes"])){

				

			$site= new Site();
			$site->name=$value["attributes"]["name"];
			$site->admin=$value["attributes"]["admin"];
			$site->phone=$value["attributes"]["phone"];
			$site->email=$value["attributes"]["email"];
			$site->address=$value["attributes"]["address"];

			$City= new City();
			$findCity=$City->findCity($value["attributes"]["city"]);

			$city_id=0;
			if(!$findCity){
				$City->name=$value["attributes"]["city"];
				$City->save();
				$city_id=$City->id;
			}else{
				$city_id=$findCity->id;
			}
			

			$site->city=$city_id;
			$site->lat=$value["attributes"]["lat"];
			$site->lon=$value["attributes"]["lon"];

			$Type= new Type();
			$findType=$Type->findType($value["attributes"]["type"]);

			$type_id=0;
			if(!$findType){
				$Type->name=$value["attributes"]["type"];
				$Type->save();
				$type_id=$Type->id;
			}else{
				$type_id=$findType->id;
			}

			$site->type=$type_id;

			$site->meta=$value["attributes"]["meta"];
			$site->services=$value["attributes"]["services"];
			$site->description=$value["attributes"]["description"];


			$site->save();

			foreach ($value["relathionships"]["services"]["data"] as $service) {

					$Service= new Service();
					$findByName=$Service->findByName($service["name"]);
					$service_id=0;
					if(!$findByName){
						$Service->name=$service["name"];
						$Service->save();
						$service_id=$Service->id;
					}else{
						$service_id=$findByName->id;
					}

					$SpacePlace= new ServiceSite();
					$SpacePlace->service_id=$service_id;
					$SpacePlace->site_id=$site->id;
					$SpacePlace->save();

				}

				
				foreach ($value["relathionships"]["spaces"]["data"] as $space) {

					$Space= new Space();
					$findByName=$Space->findByName($space["name"]);

					$space_id=0;
					if(!$findByName){
						$Space->name=$space["name"];
						$Space->save();
						$space_id=$Space->id;
					}else{
						$space_id=$findByName->id;
					}				

					$SpacePlace= new SpaceSite();
					$SpacePlace->space_id=$space_id;
					$SpacePlace->site_id=$site->id;
					$SpacePlace->save();
				}


			dump($value["attributes"]);
			}
		}
	//	dd($data);
	}


	 public function LoginUser(Request $request){
        if(Auth::check()){
            return response()->api("ya esta logueado",false);
        }else{
            $rules = [
                'email' => 'required|exists:users',
                'password' => 'required'
            ];

            $input = Input::only( 'email', 'password');

            $validator = Validator::make($input, $rules);
            
            if($validator->fails())
            {
                    return response()->api($validator->errors(),false);
            }

            $credentials = [
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'confirmed' => 1
            ];


            if ( ! Auth::attempt($credentials,true))
            {
                return response()->api("Hay un error con el login",false);

            }else{
                return response()->api(array("name"=>Auth::user()->name,"id"=>Auth::id()),true);
            }
        }
        

    }
    
}
