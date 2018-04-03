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
use Image;

class UserController extends Controller {

    public function profile($id) {
        $user = new user();
        $getuser = $user->getuser($id);
        return $getuser;
    }
    
  

    public function EditProfiile(Request $request) {
        if (Auth::check()) {
            $user = User::find(Auth::id());
            if ($user) {
                $user->bio = $request->get("bio");
                $user->cel = $request->get("cel");
                $user->country = $request->get("country");
                $user->email = $request->get("email");
                $user->lastname = $request->get("lastname");
                $user->name = $request->get("name");
                $user->profesion = $request->get("profesion");
                $user->save();
                return response()->api("Actualizaste tu perfil con èxito", true);
            }
            return response()->api("Hay un error al actualizar el perfil", false);
        }
        return response()->api("Intenta ingresar de nuevo para editar el perfil", false);
    }

    public function editAvatar(Request $request) {

        if(Auth::check()){
            
        
        $img = $request->get("avatar");


        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $data = base64_decode($img);
        $thumb = public_path('/avatars/' . Auth::id() . '.png');
        if (file_exists($thumb)) {
            unlink($thumb);
        }


        $success = file_put_contents($thumb, $data);

        $user = User::find(Auth::id());
        $user->avatar = '/avatars/' . Auth::id() . '.png';
        $user->save();
        return response()->api('/avatars/' . Auth::id() . '.png', true);

        }else{
            return response()->api("Hay un error al actualizar tu foto de perfil", false);
        }
        return $request->get("avatar");
    }

    public function profileIndividual() {
        if (Auth::check()) {
            $user = new user();
            $getuser = $user->getuserProfile(Auth::id());
            return $getuser;
        } else {
            return response()->api("Hay un error Al traer el perfil", false);
        }
    }

}
