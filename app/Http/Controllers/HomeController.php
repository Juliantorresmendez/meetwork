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
use Config;
use Mail;
use Kodeine\Acl\Models\Eloquent\Role;
use Auth;
use Input;
use Image;
use Log;
use Illuminate\Support\Facades\Validator;
use MobileDetect;
use Storage;

use Jenssegers\Agent\Agent;

class HomeController extends Controller {

    public function validateFacebook(Request $request) {
        return $request->all();
    }

    public function logout() {
        Auth::logout();
        return redirect()->to("/logoutPage");
    }

    public function tokenfacebook(Request $request) {


        /* $challenge= $_REQUEST["hub_challenge"];
          $verify= $_REQUEST["hub_verify_token"];

          if($verify=="abc123"){
          return $challenge;
          } */
        $input = (file_get_contents('php://input'));

        Log::error($input);


        return "verificador";
    }
    
       private function getFileUrl($key) {
        $s3 = Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $bucket = Config::get('filesystems.disks.s3.bucket');

        $command = $client->getCommand('GetObject', [
            'Bucket' => $bucket,
            'Key' => $key
        ]);

        $request = $client->createPresignedRequest($command, '+20 minutes');

        return (string) $request->getUri();
    }
    
    public function UploadDemo(){
        $sites= SiteImage::where("type",3)->where("status_id",1)->get();
     
        foreach ($sites as $site) {
      
    
                try {
                    
               
            $folderId=$site->site_id;


                $content = @file_get_contents("https://s3.amazonaws.com/meetworks/thumbnails/".$folderId."/".$site->url);
if (strpos($http_response_header[0], "200")) { 

            
            $input =$content ;
            $rand=time(); 
            $name= explode("/", "https://s3.amazonaws.com/meetworks/thumbnails/".$folderId."/".$site->url);
            $nameFil=$name[count($name)-1];
            
                $small = Image::make($input)->resize(40,40);
                $small = $small->stream();
                Storage::disk('s3')->put('/thumbnails/'.$folderId."/logo/".$rand."_small_".$nameFil, $small->__toString());

                
                $medium = Image::make($input)->resize(76,76);
                $medium = $medium->stream();
                Storage::disk('s3')->put('/thumbnails/'.$folderId."/logo/".$rand."_medium_".$nameFil, $medium->__toString());

                
                 
                $full = Image::make($input);
                $full = $full->stream();
                Storage::disk('s3')->put('/full/'.$folderId."/logo/".$rand."_full_".$nameFil, $full->__toString());
                
                
                $site= Site::find($site->site_id);
                $site->logo=$rand."_full_".$nameFil; 
                $site->save();
            
            
                    } else { 
   echo "FAILED" .$site->site_id;
} 
 } catch (\Exception $exc) {
                    echo $exc->getTraceAsString()." aquii ".$site->site_id;
                }


         //   dd($SiteImage);
         
        }
     
        
    }

    public function setowner() {

        $users = User::all();
        $contador = 0;
        foreach ($users as $user) {
            if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                
            } else {
                if ($user->email != "Por definir") {
                    $site = new Site();

                    $usr = new User();
                    if (!$usr->getUserByEmail($site->email)) {
                        $getSiteEmail = $site->getSiteEmail($user->id);
                        $userM = User::find($user->id);
                        $userM->email = $getSiteEmail->email;
                        $userM->save();
                    }
                }
            }
            $contador++;
        }
        die();
        $sites = Site::all();

        foreach ($sites as $site) {
            $password = str_random(8);
            if (!is_numeric($site->admin)) {
                $usr = new User();
                if (!$usr->getUserByEmail($site->email)) {
                    $userModel = User::create([
                                'name' => $site->admin,
                                'email' => $site->email,
                                'password' => bcrypt($password),
                    ]);

                    $ModSite = Site::find($site->id);
                    $ModSite->admin = $userModel->id;
                    $ModSite->save();
                }
            }
        }
    }

    public function createSlug($str, $delimiter = '') {

        $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
        return $slug;
    }

    public function demoImage(Request $request) {



        return '<img src="' . Image::url('/uploads/45/0.jpg', 300, 300, array('crop', 'grayscale')) . '"/>';
        ;


        dd();
        ini_set("memory_limit", -1);



        $folderId = 99;

        $sites = Site::find($request->get("id"));

        $folderId = $sites->id;
        try {



            $files = File::allFiles(public_path('uploads/' . $folderId));
            if (!file_exists(public_path('thumbs/' . $folderId))) {
                mkdir(public_path('thumbs/' . $folderId), 0777, true);
            }
            foreach ($files as $file) {
                $url = explode("/", (string) $file);
                $explodeUrl = $url[count($url) - 1];
                $nameOfFile = explode(".", $explodeUrl);
                $explodeUrl[1] = strtolower($explodeUrl[1]);

                if (is_numeric($nameOfFile[0])) {

                    $full = public_path('/uploads/' . $folderId . '/' . $nameOfFile[0] . '.' . $nameOfFile[1]);

                    $thumb = public_path('/uploads/' . $folderId . '/' . $nameOfFile[0] . '.' . $nameOfFile[1]);
                    Image::make($full, array(
                        'width' => 800,
                        'height' => 500,
                        'crop' => true,
                    ))->save($thumb);



                    /* 	 $SiteImage= new SiteImage();
                      $SiteImage->url=url('/uploads/'.$folderId.'/'.$nameOfFile[0].'.'.$nameOfFile[1]);
                      $SiteImage->type=1;
                      $SiteImage->site_id=$folderId;
                      $SiteImage->save(); */
                } else {
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

                      ))->save($thumb); */
                }
            };
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $siguiente = $folderId + 1;

        echo "<script>window.onload = function(e){ 
 setTimeout(function () {
        window.location='http://localhost/Laravel-Vue/public/demoImage?id=$siguiente'

    }, 2000);



}</script>";
    }

    
    
    public function searchAdmin(Request $request){
        $user= new User();
        return array("items"=>$user->searchusers($request->get("name")));
    }
    public function createRolAdmin($id) {
        /* $roleAdmin = new Role();
          $roleAdmin->name = 'Administrator';
          $roleAdmin->slug = 'administrator';
          $roleAdmin->description = 'manage administration privileges';
          $roleAdmin->save(); */

        $user = User::find($id); 
// by object
// or by id
        $user->assignRole(1);
    }

    public function sendMail(Request $request) {
        $name = $request->get("name");
        $cel = $request->get("cel");
        $barrio = $request->get("barrio");
        $product = $request->get("product");
        Mail::send('email.simple', array("name" => $name, "cel" => $cel, "barrio" => $barrio, "product" => $product), function($message) use ($request) {
            $message->to("informacion@cristiangarcia.co", "Producto")
                    ->subject("datosProucto");
        });

        return $request->all();
    }

    public function getUrl($address) {
        $ch = curl_init();
        $url = 'http://maps.google.com/maps/api/geocode/json?address=' . urlencode($address) . ',buenos+aires,ciudad+autonoma+de+buenos+aires&sensor=false';
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $result = curl_exec($ch);
        curl_close($ch);


        $output = json_decode($result);
        if (isset($output->results[0])) {
            $lat = $output->results[0]->geometry->location->lat;

            $long = $output->results[0]->geometry->location->lng;
            return ["lat" => $lat, "lon" => $long];
        } else {
            dd($output);
        }
    }
    
    
    public function index(){
        
            $agent = new Agent();
           if($agent->isMobile()){
              return  redirect()->to("/map");
           }else{
              return view('layouts.master');
           }

    }

    public function formatDirections() {
        $sites = Site::where("lat", "47.99")->get();

        foreach ($sites as $key => $value) {
            $site = Site::find($value->id);


            $json = $this->getUrl($site->address);

            $site->lat = $json["lat"];
            $site->lon = $json["lon"];
            $site->save();
        }

        return $sites;
    }

    public function formatPlaces() {

        $json = File::get("places.json");
        $data = json_decode($json, true);

        foreach ($data["data"] as $key => $value) {



            if (isset($value["attributes"])) {



                $site = new Site();
                $site->name = $value["attributes"]["name"];
                $site->admin = $value["attributes"]["admin"];
                $site->phone = $value["attributes"]["phone"];
                $site->email = $value["attributes"]["email"];
                $site->address = $value["attributes"]["address"];

                $City = new City();
                $findCity = $City->findCity($value["attributes"]["city"]);

                $city_id = 0;
                if (!$findCity) {
                    $City->name = $value["attributes"]["city"];
                    $City->save();
                    $city_id = $City->id;
                } else {
                    $city_id = $findCity->id;
                }


                $site->city = $city_id;
                $site->lat = $value["attributes"]["lat"];
                $site->lon = $value["attributes"]["lon"];

                $Type = new Type();
                $findType = $Type->findType($value["attributes"]["type"]);

                $type_id = 0;
                if (!$findType) {
                    $Type->name = $value["attributes"]["type"];
                    $Type->save();
                    $type_id = $Type->id;
                } else {
                    $type_id = $findType->id;
                }

                $site->type = $type_id;

                $site->meta = $value["attributes"]["meta"];
                $site->services = $value["attributes"]["services"];
                $site->description = $value["attributes"]["description"];


                $site->save();

                foreach ($value["relathionships"]["services"]["data"] as $service) {

                    $Service = new Service();
                    $findByName = $Service->findByName($service["name"]);
                    $service_id = 0;
                    if (!$findByName) {
                        $Service->name = $service["name"];
                        $Service->save();
                        $service_id = $Service->id;
                    } else {
                        $service_id = $findByName->id;
                    }

                    $SpacePlace = new ServiceSite();
                    $SpacePlace->service_id = $service_id;
                    $SpacePlace->site_id = $site->id;
                    $SpacePlace->save();
                }


                foreach ($value["relathionships"]["spaces"]["data"] as $space) {

                    $Space = new Space();
                    $findByName = $Space->findByName($space["name"]);

                    $space_id = 0;
                    if (!$findByName) {
                        $Space->name = $space["name"];
                        $Space->save();
                        $space_id = $Space->id;
                    } else {
                        $space_id = $findByName->id;
                    }

                    $SpacePlace = new SpaceSite();
                    $SpacePlace->space_id = $space_id;
                    $SpacePlace->site_id = $site->id;
                    $SpacePlace->save();
                }


                dump($value["attributes"]);
            }
        }
        //	dd($data);
    }

    public function getAdmiSites(Request $request) {
        $site = new Site();
        $searchAdminSites = $site->searchAdminSites($request);
        return $searchAdminSites;
    }
    
     public function getUserSites(Request $request) {
         if(Auth::check()){
            $site = new Site();
            $searchAdminSites = $site->searchUserSites($request,Auth::id());
            return $searchAdminSites;
         }else{
             return response()->api("Ingresa el nombre", false);
         }
       
    }

    
    
    public function submitRecomendation(Request $request) {
        $user = $request->get("user");
        if (!$user["name"]) {
            return response()->api("Ingresa el nombre", false);
        }
        if (!$user["email"]) {
            return response()->api("Ingresa el correo electrónico", false);
        }
        if (!$user["cel"]) {
            return response()->api("Ingresa el célular", false);
        }

        Mail::send('email.recomendation', array("user" => $request->get("user"), "lat" => $request->get("lat"), "lon" => $request->get("lon")), function($message) use ($request) {
            $message->to("reservas@meetwork.co", "Meetwork")
                    ->subject('Peticion de un nuevo lugar de ' . $request->get("user")["name"]);
        });
        dd($request->all());
    }
    
    public function login(){
        if(Auth::check()){
            return redirect()->to("/");
        }else{
            return view('layouts.master');
        }
    }
    

    public function resetPasswordByEmail(Request $request){
        
        $user= new User();
        $getUserByEmail=$user->getUserByEmail($request->get("email"));
        if($getUserByEmail){
            $this->sendEmail($getUserByEmail->id);
            return response()->api("Revisa tu correo, allí podrás restablecer tu contraseña", true);
        }else{
                return response()->api("No encontramos el usuario, intenta mas tarde", false);

        }
        return $request->all();
    }
    
    
    public function linksocialData(Request $request){

         $rules = [
                'email' => 'required|email|max:255|exists:users',
                'password' => 'required'
            ];

            $input = Input::only('email', 'password');

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return response()->api($validator->errors(), false);
            }
            
            $credentials = [
                'email' => Input::get('email'),
                'password' => Input::get('password'),
            ];
            
             if (!Auth::attempt($credentials, true)) {
              
                                    return response()->api(array("error" => array("Verifica tu contraseña, no concuerda con el usuario ".Input::get('name'))), false);

                    
             } else {
 
                    $user=User::find(Auth::id());
                    $user->social=$request->get('hash');
                    $user->save();
                    
                    return response()->api(array("name" => Auth::user()->name, "id" => Auth::id()), true);

     
                }
        
        return $request->all();
    }
    
    public  function sendWelcome($id){
        $user= User::find($id);
        if($user){
             Mail::send('email.welcomeMeetwork', array("user" => $user), function($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Bienvenido a meetwork');
                                    echo "Envio con exito al usuario  ".$user->email;

                });
        }else{
            echo 'verifica que ese id sea de un usuario y que exista';
        }
        
    }
    
    public function LoginUser(Request $request) {
        if (Auth::check()) {
            return response()->api("ya esta logueado", false);
        } else {
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required'
            ];

            $input = Input::only('email', 'password');

            $validator = Validator::make($input, $rules);

            if ($validator->fails()) {
                return response()->api($validator->errors(), false);
            }

            $credentials = [
                'email' => Input::get('email'),
                'password' => Input::get('password'),
                'confirmed' => 1
            ];
         
            if(Input::get('social')!=null){
                $user=new User();
                $getUserByEmail= $user->getUserByEmail($request->get("email"));
                if($getUserByEmail){
                    
                
                    if(isset($getUserByEmail->social)){
                        $getUserByEmailSocial=$user->getUserByEmailSocial(Input::get('email'), Input::get('social'));

                        if($getUserByEmailSocial){
                            Auth::login($getUserByEmailSocial, true);
                        }else{
                            return response()->api(array("error" => array("Verifica el usuario o la contraseña")), false);
                        }
                    }else{
                        return response()->api(array("message"=>"Ayudanos a verificar tu identidad","vinculacion"=>"si"), false);

                    }
                }else{
                        return response()->api(array("error" => array("No estas registrado en Meetwork")), false);
                }
                

            }else{
                if (!Auth::attempt($credentials, true)) {
                     $user=new User();
                    $getUserByEmail= $user->getUserByEmail($request->get("email"));
                    if($getUserByEmail->social){
                        return response()->api(array("error" => array("Contraseña invalida, tienes una cuenta de Facebook vinculada en meetwork, ¿quieres ingresar con ella?")), false);
                    }else{
                        return response()->api(array("error" => array("Verifica el usuario o la contraseña")), false);
                    }


                    
                } else {
                    return response()->api(array("name" => Auth::user()->name, "id" => Auth::id()), true);
                }
            }


            
        }
    }
    
    
    public function processCurl($method,$fb_access_token){
        $fb_graph_url = "https://graph.facebook.com/v2.12/"; //version actual al 03/2018 es v2.12
        $ch = curl_init($fb_graph_url. $method."?access_token=" . $fb_access_token);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $result=json_decode($content,true);
        return $result;
    }
    
    
    public function processCurlAnd($method,$fb_access_token){
        $fb_graph_url = "https://graph.facebook.com/v2.12/"; //version actual al 03/2018 es v2.12
        
         $ch = curl_init($fb_graph_url. $method."?access_token=" . $fb_access_token);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $result=json_decode($content,true);
        return $result;
    }
    
       
    public function processCurlAnds($method,$fb_access_token){
        $fb_graph_url = "https://graph.facebook.com/v2.12/"; //version actual al 03/2018 es v2.12
        dd($fb_graph_url. $method."&access_token=" . $fb_access_token);
        $ch = curl_init($fb_graph_url. $method."&access_token=" . $fb_access_token);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        $result=json_decode($content,true);
        return $result;
    }
    
    
    public function subscribeDemo(){
                $processCurl=$this->processCurl("/me/accounts/",'EAABb1HX9ovwBACy32ZAZCJzyMfg32KZBWOKN6GQyhaxqkQPCzgnKjqueg1rzZCGukS5YT2YU89dwUDrAFIzGSfudxDWcUZCqTRvSw3Oata4wrZB8i1B0vbalBCk8gGXstZCkbWVOzC1dfvr5jZBi3WpQZASZATT2zWATgZD');

                 foreach ($processCurl['data'] as $value) {
                        if($value['id']=='618736968474165'){
                                 $processCurlForms= $this->processCurlAnds($value['id']."/subscriptions?verify_token=abc123&fields=leadgen&object=page&callback_url=https://meetwork.co/processLeads",$value['access_token']);
                      
                                dump($processCurlForms);
                                
                        }
                 }
                 dd();
                
    }
    
    public function processLeads(){
      //  $this->subscribeDemo();
        
    /*    error_reporting(E_ALL);
ini_set('display_errors', 1);
$challenge= $_REQUEST["hub_challenge"];
		 $verify= $_REQUEST["hub_verify_token"];
		 if($verify=="abc123"){
			 echo  $challenge;
	 }else{
     echo  $challenge;
   }
   die();*/
        
        $processCurl=$this->processCurl("/me/accounts/",'EAABb1HX9ovwBACy32ZAZCJzyMfg32KZBWOKN6GQyhaxqkQPCzgnKjqueg1rzZCGukS5YT2YU89dwUDrAFIzGSfudxDWcUZCqTRvSw3Oata4wrZB8i1B0vbalBCk8gGXstZCkbWVOzC1dfvr5jZBi3WpQZASZATT2zWATgZD');

        foreach ($processCurl['data'] as $value) {
            echo "<pre>";
            if($value['id']=='618736968474165'){
                
                        $processCurlForms= $this->processCurl($value['id']."/leadgen_forms",$value['access_token']);
                         
                           
                           foreach ($processCurlForms["data"] as $valuef) {
                              
                                $processCurlFormsdata= $this->processCurlAnd($valuef["id"].'/leads',$value['access_token']);
                                if(count($processCurlFormsdata["data"])>0){
                                    foreach ($processCurlFormsdata["data"] as $valuet) {
                                            dump(date( "Y-m-d , g:i a", strtotime( $valuet["created_time"] ) ));

                                        foreach ($valuet["field_data"] as $values) {
                                            //print_r($values["name"]);
                                            if($values["name"]=='full_name'){
                                               $values["name"]="Nombre"; 
                                            }
                                            
                                            if($values["name"]=='phone_number'){
                                               $values["name"]="Numero de telefono"; 
                                            }
                                            echo "<b>". strtoupper($values["name"])."</b> ".$values["values"][0]."<br>";
                                        }
                                        
                                        echo '<hr>';
                                    }


                                }
                                
                           }

                           
            }

        }
    }
    
    
    public function sendEmail($id){
        
      /*  
        foreach (Site::all() as $value) {
            if($value->user){
                            dump("sitio: ".$value->name."-> admin: ".$value->user->name);  
            }else{
                            dump($value->name." sin admin ");  
            }
        }
        
        dd();*/
        
        $code=bcrypt(time());
        $user= User::find($id);
        $user->remember_password=$code;
        $user->save();
                Mail::send('email.petitionPasswordReset', array("code"=>$code,"user" => $user), function($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Restablecer tu contraseña');
                });
                return redirect()->to("/sendValidation?email=".$user->email);

    }
    
    protected function validatorRecoverPassword(array $data) {
        return Validator::make($data, [
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    public function changePasswordUser(Request $request) {

        $validator = $this->validatorRecoverPassword($request->all());
        if ($validator->fails()) {
            return response()->api($validator->errors(), false);
        }


        $user = new User();
        $getByRememberPassword = $user->getByRememberPassword($request->get("token_recover"));
        if ($getByRememberPassword) {
            $getByRememberPassword->password = bcrypt($request->get("password"));
            $getByRememberPassword->remember_password = null;
            $getByRememberPassword->save();

            Auth::login($getByRememberPassword, true);
                $user= Auth::user();
                Mail::send('email.resetPassword', array("user" => $user), function($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Se ha cambiado tu contraseña');
                });
            return response()->api(array("name" => Auth::user()->name, "id" => Auth::id()), true);
        } else {
            return response()->api(array("error" => array("Verifica las contraseñas o vuelve a pedir el reseteo de la contraseña")), false);
        }
        return ($request->all());
    }

}
