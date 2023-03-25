<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    //register function
    public function register(Request $req){
        $fields = $req->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'class' => 'required|string',
            'password' => 'required|string|confirmed',

        ]);

        $user = User::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'email' => $fields['email'],
            'class' => $fields['class'],
            'password' => Hash::make($fields['password'])
        ]);

        $token = $user->createToken($user->email.'-Token')->plainTextToken;

        $res = [
            'message'=>'User has been created successfully!',
            'user' => $user,
            'token' => $token
        ];

        return response($res, 201);
    }

    public function login(Request $req){

        $req->validate([
            'email'=>'required|string',
            'password'=>'required|string',
        ]);

        if(!Auth::attempt($req->only(['email','password']))){
            return response('Credentials do not match our records, check and try again', 401);
        }

        $user = User::where('email', $req->email)->first();

        $res = [
            'message'=>'User has logged in succesfully!',
            'user' => $user,
            'token' => $user->createToken($user->email.'-Token')->plainTextToken
        ];

        return response($res, 201);

    }

    public function logout(Request $req){
        $req->user()->currentAccessToken()->delete();
        return response("You have logged out!", 200);
    }
    
    //Revoke all tokens on all devices previously logged in
    public function logout_all_sessions(Request $req){
        $req->user()->tokens()->delete();
        return response("You have logged out!", 200);
    }
}
