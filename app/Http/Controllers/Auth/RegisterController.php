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
class RegisterController extends Controller
{
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
    public function __construct()
    {
        $this->middleware('guest');
    }

    

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function registerAccount(Request $request){
        if($request->get("password")==$request->get("password_confirmation")){

            $validator=$this->validator($request->all());
            if($validator->fails()){
                return response()->api($validator->errors(),false);
            }

            $user=$this->create($request->all());
            if($user){
                $confirmation_code = str_random(30);

                 $user= User::find($user->id);
                 $user->confirmation_code=$confirmation_code;
                 $user->save();
                 Session::put('email',$user->email);
                 Session::put('confirmation_code',$confirmation_code);

                 Mail::send('email.verify', array("confirmation"=>$confirmation_code,"email"=>$user->email), function($message) use ($request) {
                    $message->to($request->get("email"), $request->get("name"))
                        ->subject('Validación cuenta de Meetwork');
                });
                return response()->api($user->email,true);
            }
        }else{
            return response()->api("verifica las contraseñas",false);
        }
    }

    public function verifyAccount(Request $request,$code,$email){
        $user= new User();
        $getUserByActivation=$user->getUserByActivation($code,$email);
        if(!$getUserByActivation){
            if($request->has("page")){
                return response()->api("validacion no",false);

            }else{
                return redirect("/#/already_confirmation");
            }

        }
        $getUserByActivation->confirmation_code=null;
        $getUserByActivation->confirmed=1;

        $getUserByActivation->save();



        if($request->has("page")){
            return response()->api("validacion ok",true);
        }else{
            return redirect("/#/login");
        }

    }

    public function resendCode(Request $request){
         $confirmation_code =Session::get('confirmation_code');
         $email=Session::get('email');
        
        $user= new User();
        
        $getUserByActivation=$user->getUserByActivation($confirmation_code,$email);
        $email=$getUserByActivation->email;
        $name=$getUserByActivation->name;
        Mail::send('email.verify', array("confirmation"=>$confirmation_code,"email"=>$email), function($message) use ($email,$name) {
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
    protected function create(array $data)
    {

       
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
