<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $req){
        $data = $req->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed'
        ]);
        $user = User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password'])
        ]);
        // create cart
        $user->cart()->create();
        $token = auth()->login($user);
        return response()->json(['user'=>$user,'token'=>$token],201);
    }

    public function login(Request $req){
        $creds = $req->only(['email','password']);
        if(!$token = auth()->attempt($creds)){
            return response()->json(['message'=>'Invalid credentials'],401);
        }
        return response()->json(['token'=>$token,'user'=>auth()->user()]);
    }

    public function logout(){
        auth()->logout();
        return response()->json(['message'=>'Logged out']);
    }

    public function me(){ return response()->json(auth()->user()); }
}
