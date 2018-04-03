<?php

namespace App\Http\Controllers;

use App\Note;
use App\User;
use Illuminate\Http\Request;
use File;
use App\Site;
use App\UserReservation;
use Auth;
use Mail;
use App\City;
use App\Type;
use App\Service;
use App\Space;
use App\SpaceSite;
use App\SiteImage;
use App\ServiceSite;
use Image;
use Storage;

class SiteController extends Controller {

    public function searchSites(Request $request) {
        $site = new Site();
        return $site->searchSites($request);
    }

     public function acceptReserv(Request $request, $id) {

        if (Auth::check()) {
            $site = UserReservation::find($id);
            if ($site) {
                 $user = Auth::user();
            $admin = $user->isAdministrator();
            if (Auth::id() == $site->admin|| $admin) {

                    $site->status_id = 3;
                        $site->description = $request->get("descriptionDecline");
                    
                    $site->save();
                    Mail::send('email.acceptOwner', array("reserv" => $site), function($message)use ($site) {
                        $message->to("reservas@meetwork.co", "MeetWork")
                                ->subject('Un Dueño Ha aceptado una reserva de ' . $site->site->name);
                    });

                    Mail::send('email.infoconfirmreserv', array("reserv" => $site), function($message)use ($site) {
                        $message->to($site->user->email, $site->user->name)
                                ->subject($site->site->name." ha confirmado tu reserva");
                    });
                    
                    return response()->api("Se ha aceptado con éxito la reservación", true);
                }
                return response()->api("El usuario no coincide con la persona que declino la reservación", false);
            } else {
                return response()->api("No existe la reservación", false);
            }
        }
        return response()->api("Debe estar logueado para poder declinar la reservación", false);
    }
    
    
    
    public function declineReserv(Request $request, $id) {
        if (Auth::check()) {
            $site = UserReservation::find($id);
            if ($site) {
                 $user = Auth::user();
            $admin = $user->isAdministrator();
            if (Auth::id() == $site->admin|| $admin) {

                    $site->status_id = 4;
                    if ($request->has("descriptionDecline")) {
                        $site->description = $request->get("descriptionDecline");
                    }
                    $site->save();
                    Mail::send('email.declineOwner', array("reserv" => $site), function($message)use ($site) {
                        $message->to("reservas@meetwork.co", "MeetWork")
                                ->subject('Un Dueño Ha declinado una reserva de ' . $site->site->name);
                    });

                      Mail::send('email.infoconfirmnoreserv', array("reserv" => $site), function($message)use ($site) {
                        $message->to($site->user->email, $site->user->name)
                                ->subject($site->site->name." ha dado respuesta a tu reserva");
                    });
                    
                    return response()->api("Se ha declinado con éxito la reservación", true);
                }
                return response()->api("El usuario no coincide con la persona que declino la reservación", false);
            } else {
                return response()->api("No existe la reservación", false);
            }
        }
        return response()->api("Debe estar logueado para poder declinar la reservación", false);
    }

    public function getReservations($id) {
        if (Auth::check()) {

            $site = Site::find($id);
            if ($site) {
                
                $user = Auth::user();
            $admin = $user->isAdministrator();
            if (Auth::id() == $site->admin || $admin) {
                
                    $userReservation = new UserReservation();
                    $getPastSiteReservation = $userReservation->getPastSiteReservation($id);
                    $getCurrentSiteReservation = $userReservation->getCurrentSiteReservation($id);
                    $getFutureSiteReservation = $userReservation->getFutureSiteReservation($id);
                    return array("past" => $getPastSiteReservation, "current" => $getCurrentSiteReservation, "future" => $getFutureSiteReservation);
                }
                return response()->api("No eres el dueño de este sitio", false);
            }
            return response()->api("No hay un sitio con ese id", false);
        } else {
            return response()->api("No estas logueado por favor ingresa a Meetwork", false);
        }
    }

    public function uploadPhotoSite(Request $request) {

        if (Auth::check()) {
            $folderId = $request->get("site_id");
            $site = Site::find($folderId);
            $user = Auth::user();
            $admin = $user->isAdministrator();
            //dd($folderId);
            if (Auth::id() == $site->user->id || $admin) {


             $rand=time();

             
            $full = Image::make($request->file("file"))->fit(800,500);
            $full = $full->stream();
            Storage::disk('s3')->put('/full/'.$folderId."/".$rand."_".$request->file("file")->getClientOriginalName(), $full->__toString());

            
            $thumbImage = Image::make($request->file("file"))->fit(340,210);
            $thumbImage = $thumbImage->stream();
            Storage::disk('s3')->put('/thumbnails/'.$folderId."/".$rand."_".$request->file("file")->getClientOriginalName(), $thumbImage->__toString());

             
                $SiteImage = new SiteImage();
                $SiteImage->url = $rand."_".$request->file("file")->getClientOriginalName();
                $SiteImage->type = 1;
                $SiteImage->site_id = $folderId;
                $SiteImage->size = $request->file("file")->getClientSize();
                $SiteImage->save();

                return "https://s3.amazonaws.com/meetworks/thumbnails/".$folderId."/".$rand."_".$request->file("file")->getClientOriginalName()."|".$SiteImage->id;
            } else {
                return response()->api("Falla al momento de modificar la imágen", false);
            }
        }
    }

    public function uploadLogoSite(Request $request) {

        if (Auth::check()) {
            $folderId = $request->get("site_id");
            $site = Site::find($folderId);
            $user = Auth::user();
            $admin = $user->isAdministrator();
            if (Auth::id() == $site->user->id || $admin) {
                
                
                
                if($site->logo==""){
                    
                $rand=time();

                $small = Image::make($request->file("file"))->fit(40,40);
                $small = $small->stream();
                Storage::disk('s3')->put('/thumbnails/'.$folderId."/logo/".$rand."_small_".$request->file("file")->getClientOriginalName(), $small->__toString());

                
                $medium = Image::make($request->file("file"))->fit(76,76);
                $medium = $medium->stream();
                Storage::disk('s3')->put('/thumbnails/'.$folderId."/logo/".$rand."_medium_".$request->file("file")->getClientOriginalName(), $medium->__toString());

                
                 
                $medium = Image::make($request->file("file"));
                $medium = $medium->stream();
                Storage::disk('s3')->put('/full/'.$folderId."/logo/".$rand."_full_".$request->file("file")->getClientOriginalName(), $medium->__toString());


          
              /*  Image::make($request->file("file"), array(
                    'width' => 30,
                    'height' => 30,
                    'crop' => true,
                ))->save(public_path('/thumbs/'.$folderId.'/logo-small.png'));


                Image::make($request->file("file"), array(
                    'width' => 76,
                    'height' => 76,
                    'crop' => true,
                ))->save(public_path('/thumbs/'.$folderId.'/logo-medium.png'));


                
                Image::make($request->file("file"), array(
                ))->save(public_path('/thumbs/'.$folderId.'/logo-full.png'));
*/
                $site->logo=$rand."_full_".$request->file("file")->getClientOriginalName();           
                $site->save();
                /*$SiteImage = new SiteImage();
                $SiteImage->url = $rand."_small_".$request->file("file")->getClientOriginalName();
                $SiteImage->type = 3;
                $SiteImage->site_id = $folderId;
                $SiteImage->size = $request->file("file")->getClientSize();
                
                $SiteImage->save();*/
                return 'https://s3.amazonaws.com/meetworks/full/'.$folderId."/logo/".$rand."_full_".$request->file("file")->getClientOriginalName()."|".$rand."_small_".$request->file("file")->getClientOriginalName();
            }else{
            
                return response()->json("No se puede subir mas logos, intenta borrar el logo actual", 404);  

            }
            } else { 
                return response()->api("Falla al momento de modificar la imágen", false);
            }
        }
    }

    
    public function editadmin($id){
        if(Auth::check()){
            
        
        $site=Site::find($id);
        if($site){
            $user = Auth::user();
            $admin = $user->isAdministrator();
            if($site->admin==Auth::id() || $admin){
                return view('layouts.master');
            }else{
                return redirect()->to("/");
            }
        }else{
            return redirect()->to("/");
        }
        }else{
            return redirect()->to("/");
        }

    }
    
    
    public function reservations($id,$name){
        if(Auth::check()){
            $site=Site::find($id);
            if($site){
                $user = Auth::user();
                $admin = $user->isAdministrator();
                if($site->admin==Auth::id() || $admin){
                    return view('layouts.master');
                }else{
                    return redirect()->to("/");
                }
            }else{
                return redirect()->to("/");
            }
        }else{
                return redirect()->to("/login");
        }
        
    }
    
    public function admin(){
         if(Auth::check()){
            $user = Auth::user();
            $admin = $user->isAdministrator();
            if(!$admin){
                return redirect()->to("/");
            }else{
                return view('layouts.master');
            }
         }
    }
    
    public function getFilters() {
        $Service = Service::select("id", "name")->get();
        $Space = Space::select("id", "name")->get();
        return ["services" => $Service, "spaces" => $Space];
    }

    public function getIndividual($id) {
        return Site::with("services")
                        ->with("spaces")
                        ->with("images")
                        //->with("logo")
                        ->with("city")
                        ->with(array('reviews' => function($query) {
                                $query->with('user');
                                $query->orderBy('created_at', "desc");
                            }))->find($id);
    }

    public function postReserv(Request $request, $id) {
        if (Auth::check()) {
            $userReservation = new UserReservation();
            $getUserRegistration = $userReservation->getUserRegistration(Auth::id(), $id, $request->get("time"), $request->get("hour"));
            if ($getUserRegistration->count() == 0) {

                $hours = explode(":", $request->get("hour"));
                $userReservation->number_person = $request->get("numberOfPersons");
                $userReservation->date_reservation = $request->get("time");
                $userReservation->time = $hours[0];
                $userReservation->site_id = $id;
                $userReservation->user_id = Auth::id();
                $userReservation->save();

                Mail::send('email.inforeserv', array("reserv" => $userReservation), function($message) use ($userReservation) {
                    $message->to($userReservation->user->email, $userReservation->user->name)
                            ->subject('Información de tu reserva');
                });

                   Mail::send('email.petition', array("reserv" => $userReservation), function($message)use ($userReservation) {
                        $message->to("reservas@meetwork.co", "MeetWork")
                                ->subject('reserva en curso de  ' . $userReservation->site->name);
                    });
                        
                        
                        
                        
                /*

                  Mail::send('email.infoconfirmreserv', array("reserv"=>$userReservation), function($message) use ($userReservation) {
                  $message->to($userReservation->user->email, $userReservation->user->name)
                  ->subject('Reserva Confirmada');
                  });

                  Mail::send('email.infoconfirmnoreserv', array("reserv"=>$userReservation), function($message) use ($request) {
                  $message->to("Juliantor2@gmail.com", "Meetwork")
                  ->subject('¡ Seguimos creciendo !');
                  });
                  Mail::send('email.welcome', array("user"=>$userReservation->user), function($message) use ($request) {
                  $message->to("Juliantor2@gmail.com", "Meetwork")
                  ->subject('Bienvenido a MeetWork');
                  });
                 *   
                 *                 */
            } else {
                return response()->api("Ya reservaste este lugar para esta fecha y hora", false);
            }

            return response()->api("Se ha reservado con éxito", true);
        } else {
            return response()->api("No estas logueado ingresa a la pantalla de ingreso", false);
        }
    }

    public function editAdminSite(Request $request) {
        if (Auth::check()) {
            $site = Site::find($request->get("id"));

            $user = Auth::user();
            $admin = $user->isAdministrator();
            if (!$admin) {
                return response()->api("no se puede editar este sitio", false);
            }
            $site->name = $request->get("name");
            $City = new City();
            $findCity = $City->findCity($request->get("city"));

            $city_id = 0;
            if (!$findCity) {
                $City->name = $request->get("city");
                $City->save();
                $city_id = $City->id;
            } else {
                $city_id = $findCity->id;
            }



            $site->city = $city_id;



            $site->description = $request->get("description");
            $site->email = $request->get("email");
            $site->address = $request->get("address");
            $site->type = 12;

            $site->lat = $request->get("lat");
            $site->lon = $request->get("lon");
            $site->phone = $request->get("phone");
            $site->status_id = $request->get("status_id");

            $services = $this->formatArray($request->get("selectedServices"));
            $spaces = $this->formatArray($request->get("selectedSpace"));

            $site->services = implode(",", $services);
            $site->meta = implode(",", $spaces);
            $site->save();


            $ServiceSiteModel = new ServiceSite();
            $ServiceSiteModel->deleteAllBySite($site->id);

            $SpaceSiteModel = new SpaceSite();
            $SpaceSiteModel->deleteAllBySite($site->id);


            foreach ($services as $service) {
                $Service = new Service();
                $findByName = $Service->findByName($service);
                $service_id = 0;
                if (!$findByName) {
                    $Service->name = $service;
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


            foreach ($spaces as $space) {

                $Space = new Space();
                $findByName = $Space->findByName($space);

                $space_id = 0;
                if (!$findByName) {
                    $Space->name = $space;
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
        }
        return response()->api("Edición con éxito", true);
    }

    public function formatArray($array) {
        $arrayFinal = array();
        foreach ($array as $value) {
            if (isset($value["id"])) {
                $arrayFinal[] = $value["name"];
            } else {
                $arrayFinal[] = $value;
            }
        }
        return $arrayFinal;
    }

    public function editSite(Request $request) {
        if (Auth::check()) {
            $site = Site::find($request->get("id"));

            if ($site->admin != Auth::id()) {
                return response()->api("no se puede editar este sitio", false);
            }
            $site->name = $request->get("name");
            $City = new City();
            $findCity = $City->findCity($request->get("city"));

            $city_id = 0;
            if (!$findCity) {
                $City->name = $request->get("city");
                $City->save();
                $city_id = $City->id;
            } else {
                $city_id = $findCity->id;
            }



            $site->city = $city_id;



            $site->description = $request->get("description");
            $site->email = $request->get("email");
            $site->address = $request->get("address");
            $site->type = 12;

            $site->lat = $request->get("lat");
            $site->lon = $request->get("lon");
            $site->phone = $request->get("phone");

            $services = $this->formatArray($request->get("selectedServices"));
            $spaces = $this->formatArray($request->get("selectedSpace"));

            $site->services = implode(",", $services);
            $site->meta = implode(",", $spaces);
            $site->save();


            $ServiceSiteModel = new ServiceSite();
            $ServiceSiteModel->deleteAllBySite($site->id);

            $SpaceSiteModel = new SpaceSite();
            $SpaceSiteModel->deleteAllBySite($site->id);


            foreach ($services as $service) {
                $Service = new Service();
                $findByName = $Service->findByName($service);
                $service_id = 0;
                if (!$findByName) {
                    $Service->name = $service;
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


            foreach ($spaces as $space) {

                $Space = new Space();
                $findByName = $Space->findByName($space);

                $space_id = 0;
                if (!$findByName) {
                    $Space->name = $space;
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
        }
        return response()->api("Edición con éxito", true);
    }

    public function deleteAdminImage(Request $request, $site, $image) {

        if (Auth::check()) {
            $SiteImage = new SiteImage();
            $getImageBySiteImage = $SiteImage->getImageBySiteImage($site, $image);
            if ($getImageBySiteImage) {
                $user = Auth::user();
                $admin = $user->isAdministrator();
                if (!$admin) {
                    return response()->api("No se puede borrar la imágen", false);
                } else {
                    $getImageBySiteImage->status_id = 2;
                    $getImageBySiteImage->save();
                    return response()->api("Se ha borrado", true);

                }
            }
        }
                            return response()->api("no entro", true);

        return $request->all();
    }
    
    
      public function deleteLogoAdmin(Request $request, $site, $image) {

          
          
          if (Auth::check()) {
            $site=Site::find($site);
        if($site){
            $user = Auth::user();
            $admin = $user->isAdministrator();
            if($site->admin==Auth::id() || $admin){
                $site->logo="";
                $site->save();
            
                $medium="/thumbnails/".$site->id."/logo/".str_replace( "small", "medium",$image);
                $small="/thumbnails/".$site->id."/logo/".$image;
                $full="/full/".$site->id."/logo/".str_replace( "small", "full",$image);
                    
                 Storage::disk('s3')->delete($medium);
                 Storage::disk('s3')->delete($full);
                 Storage::disk('s3')->delete($small);

            }else{
                    return response()->api("No se puede borrar la imágen", false);
            }
        }
        }
          
          
          
     /*   if (Auth::check()) {
            $SiteImage = new SiteImage();
            $getImageBySiteImage = $SiteImage->getImageBySiteImage($site, $image);

            if ($getImageBySiteImage) {
                

                $user = Auth::user();
                $admin = $user->isAdministrator();
                if (!$admin) {
                    return response()->api("No se puede borrar la imágen", false);
                } else {
                    $getImageBySiteImage->status_id = 2;
                    $getImageBySiteImage->save();
                }
            }
        }*/
        return $request->all();
    }

    public function deleteImage(Request $request, $site, $image) {

        if (Auth::check()) {
            $SiteImage = new SiteImage();
            $getImageBySiteImage = $SiteImage->getImageBySiteImage($site, $image);
            if ($getImageBySiteImage) {
                if ($getImageBySiteImage->site->user->id != Auth::id()) {
                    return response()->api("No se puede borrar la imágen", false);
                } else {
                    $getImageBySiteImage->status_id = 2;
                    $getImageBySiteImage->save();
                }
            }
        }
        return $request->all();
    }

    public function publish(Request $request) {
        if (Auth::check()) {
            $site = new Site();
            $site->admin = Auth::id();
            $site->name = $request->get("name");
            $City = new City();
            $findCity = $City->findCity($request->get("city"));

            $city_id = 0;
            if (!$findCity) {
                $City->name = $request->get("city");
                $City->save();
                $city_id = $City->id;
            } else {
                $city_id = $findCity->id;
            }



            $site->city = $city_id;



            $site->description = $request->get("description");
            $site->email = $request->get("email");
            $site->address = $request->get("address");
            $site->type = 12;

            $site->lat = $request->get("lat");
            $site->lon = $request->get("lon");
            $site->phone = $request->get("phone");
            $services = $this->formatArray($request->get("selectedServices"));
            $spaces = $this->formatArray($request->get("selectedSpace"));

            $site->services = implode(",", $services);
            $site->meta = implode(",", $spaces);
            $site->save();

            foreach ($services as $service) {
                $Service = new Service();
                $findByName = $Service->findByName($service);
                $service_id = 0;
                if (!$findByName) {
                    $Service->name = $service;
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


            foreach ($spaces as $space) {

                $Space = new Space();
                $findByName = $Space->findByName($space);

                $space_id = 0;
                if (!$findByName) {
                    $Space->name = $space;
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
        }
        return $site;
    }

}
