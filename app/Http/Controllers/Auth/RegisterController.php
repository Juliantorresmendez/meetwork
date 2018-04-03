<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;
use Session;
use Auth;
use Location;
class RegisterController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Register Controller
      |--------------------------------------------------------------------------
      |
      | This controller handles the registration of new users as well as their
      | validation and creation. By default this controller uses a trait to
      | provide this functionality without requiring any additional code.
      |
     */

use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/ss';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
                    'name' => 'required|max:255',
                    'cel' => 'required|min:8|max:15',
                    'email' => 'required|email|max:255',
                    'password' => 'required|min:6|confirmed',
        ]);
    }

    public function registerAccount(Request $request) {
        if ($request->get("password") == $request->get("password_confirmation")) {

            $validator = $this->validator($request->all());
            if ($validator->fails()) {
                return response()->api($validator->errors(), false);
            }
            
            $user= new User();
            $getUserByEmail= $user->getUserByEmail($request->get("email"));
            if($getUserByEmail){
                if($getUserByEmail->social){
                    return response()->api(array("social"=>$getUserByEmail->social,"emailValidation"=>"no"), false);
                }
                    return response()->api(array("message"=>"Ya estas registrado en Meetwork, inicia sesión","emailValidation"=>"no"), false);
            }

            
            $user = $this->create($request->all());
            
            
            
            
            
            if ($user) {
                
                  $confirmation_code = str_random(10);

                $user = User::find($user->id);
                $user->confirmation_code = $confirmation_code;
                
            if($request->has("social")){
                
                $avatarFacebook='http://graph.facebook.com/'.$request->get("social").'/picture?type=large';

                
                $data = file_get_contents($avatarFacebook);

                
                 $thumb = public_path('/avatars/' . $user->id . '.png');
                if (file_exists($thumb)) {
                    unlink($thumb);
                }


                $success = file_put_contents($thumb, $data);
                $user->avatar = '/avatars/' . $user->id . '.png';

            }
                
                
                
              

                $user->save();
                Session::put('email', $user->email);
                Session::put('confirmation_code', $confirmation_code);

                Mail::send('email.verify', array("confirmation" => $confirmation_code, "user" => $user), function($message) use ($request) {
                    $message->to($request->get("email"), $request->get("name"))
                            ->subject('Validación cuenta de Meetwork');
                });
                
                
                Mail::raw('Usuario Creado'.$user->id, function ($message){
                            $message->to('informacion@cristiangarcia.co');
                 });

                
                
                
                return response()->api($user->email, true);
            }
        } else {
            return response()->api("verifica las contraseñas", false);
        }
    }

    public function verifyAccount(Request $request, $code, $email) {
        $user = new User();
        $getUserByActivation = $user->getUserByActivation($code, $email);
        if (!$getUserByActivation) {
            if ($request->has("page")) {
                return response()->api("validacion no", false);
            } else {
                return redirect("/#/already_confirmation");
            }
        }
        $getUserByActivation->confirmation_code = null;
        $getUserByActivation->confirmed = 1;

        $getUserByActivation->save();
       
        Auth::login($getUserByActivation, true);
            
        $userTemporal = Auth::user();
        Mail::send('email.welcome', array("user" => Auth::user()), function($message) use ($userTemporal) {
            $message->to($userTemporal->email, $userTemporal->name)
                    ->subject('Bienvenido a MeetWork');
        });
        if ($request->has("page")) {         
            return response()->api(array("name" => Auth::user()->name, "id" => Auth::id()), true);

        } else {
            return redirect("/");
        }
    }

    public function resendCode(Request $request) {
        $confirmation_code = Session::get('confirmation_code');
        $email = Session::get('email');

        $user = new User();

        $getUserByActivation = $user->getUserByActivation($confirmation_code, $email);
        $email = $getUserByActivation->email;
        $name = $getUserByActivation->name;
        Mail::send('email.verify', array("confirmation" => $confirmation_code, "user" => $getUserByActivation), function($message) use ($email, $name) {
            $message->to($email, $name)
                    ->subject('Validación cuenta de Meetwork');
        });
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {

 
        return User::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'social' => $data['social'],
                    'cel' => $data['cel'],
                    'ip' =>  \Request::ip(),
                    'password' => bcrypt($data['password']),
        ]);
    }

}
