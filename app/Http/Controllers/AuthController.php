<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    //register function
    public function register(Request $req){
        $fields = $req->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'file' => 'image|nullable',
            'class' => 'required|string',
            'password' => 'required|string',

        ]);

        $user = User::create([
            'firstname' => $fields['firstname'],
            'lastname' => $fields['lastname'],
            'email' => $fields['email'],
            'class' => $fields['class'],
            'password' => Hash::make($fields['password'])
        ]);

        if($req->hasFile('file')){
            $file = $req->file('file');
            $filename = $file->store('public/profile-pictures');
            $full_file_url = Storage::url($filename);

            //had to build a path which contains port 
            $base_url = Config::get('app.url').(env('APP_PORT')?":".env('APP_PORT'):"");

            $user->profile->passport = $base_url.$full_file_url;
            $user->profile->save();
        }


        $res = [
            'message'=>'User has been created successfully!',
            'user' => $user
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
            'message'=>"Logged in as ".$user->email." !",
            'data' => [
                'token' => $user->createToken($user->email.'-Token')->plainTextToken,
                'user' => $user,
                'profile' => $user->profile
            ]
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
