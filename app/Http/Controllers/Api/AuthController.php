<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //


    public function signup(SignupRequest $request){
        $data = $request->validated();
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),

            ]);
            $token= $user->createToken('main')->plainTextToken;
            return response([
                'user'=>$user,
                'token'=>$token,
            ]);
    }

    public function login(LoginRequest $request){
        $data = $request->validated();
        $remainder=$data['remember'] ?? false;
        unset($data['remember']);
        if(!Auth::attempt($data,$remainder)){
            return response([
                'error'=>'The Provided credentials are not correct'],422);
        }
        /** @var \App\Models\User $user */
        $user =Auth::user();
        $token =$user->createToken('main')->plainTextToken;
        return response([
            'user'=>$user,
            'token'=>$token
        ]);
        
    }
    public function logout(){

        $user = Auth::user();
        $user->currentAccessToken()->delete();
        return response([
            'success'=>true,
        ]);
        
    }
}
