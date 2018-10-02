<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use App\User; 
use Illuminate\Support\Facades\Auth; 
use Validator;

class UserController extends Controller
{
    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'firstName' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);
if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
$input = $request->all(); 
        $input['password'] = bcrypt($input['password']); 
        $user           = new User;
        $user->email     = $request->email;
        $user->password = bcrypt($request->password);
        $user->firstName     = $request->firstName;
        $user->lastName     = $request->lastName;
        $user->gender   = "UNKNOW";
        $user->avatar = "https://allaravel.com/wp-content/uploads/2017/05/hien-trang-ung-dung-forum-dang-spa.gif";
        $user->role = strtoupper("user");
        $user->provider = strtoupper("local");
        $user->emailVerified = false;
        $user->status = strtoupper("active");
        $user->save();
        return response()->json($user); 
    }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 
}
