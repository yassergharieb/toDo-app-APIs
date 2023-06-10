<?php

namespace App\Http\Controllers;


use App\Events\UserRegistered;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;


class AuthController extends Controller
{

  public function __construct() {
    $this->middleware(['JWTauthanticate:user-api'])->except(['Userlogin' , 'register' , 'Userlogout']); 
  }


    public function Userlogin(Request $request){ 

        $rules =  [
         'password' => 'required', 
          'email'  => 'required|exists:users,email'
                ];




        // $validator  = $request->validate( [
        //   'password' => 'required' , 
        //   'email' =>  'required|exists:users,email'
        // ]);   
        
       
       $validator =  Validator::make($request->only(['password' , 'email']) , $rules);
          
        
       if ($validator->fails()) {
        return response()->json([
          'validation falis' =>  $validator->errors()->toArray(), 
           'status' =>  302
        ]);
       }
   

        $token  =  Auth::guard('user-api')->attempt($request->only(['password' , 'email']));



        if(!$token) {
          return response()->json([
          'msg' => 'you are  not authanticated' , 
          'status' => '404'
          ]);
        } 
        $user  =  Auth::guard('user-api')->user();
        return response()->json([
          'msg' => 'you are authanticated' , 
          'data' => ['user'  =>  $user , 'token' => $token], 
        ]);
        


    }
    

  
    
    public function Userlogout(Request $request){

      $token  =   $request->header('apitoken');

    

      if(!$token) {
        return response()->json(['msg' => 'some thing went wrong' , ] , 302);
      } else {
      
        \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::setToken($token)->invalidate();

        return response()->json(['msg' => 'logged out' , ] , 200);


      }
        



    } 
    
    
    public function register(Request $request)
    {
 
           try {
            DB::beginTransaction();
            $user_id =  DB::table('users')->insertGetId([  
              'name' => $request->name, 
             'email' => $request->email, 
             'password' => bcrypt( $request->password), 
      
             ]);
      
             $user =  DB::table('users')->where('id' , '=' , $user_id)->first();
            //  dd($user->email);
         
                  
              event(new UserRegistered($user));
             
              
        
      
              if ($user) {
                DB::commit();
                  return response()->json(['data' => $user] , 200);
              }
             

           } catch ( \Exception  $e) {
               DB::rollback();
               return response()->json(['error' => $e->getMessage()]);
           }

        
     
    }



    public function UserShow (Request $request) {


     
     return Auth::user();

    }
   


}
