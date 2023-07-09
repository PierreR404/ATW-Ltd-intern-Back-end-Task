<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Hash;



class AuthController extends Controller
{
    //
    public function register(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string|unique:users,email',
            'password'=>'required|string',
            'phone'=>'required|digits:11'
        ]);
        $user = User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'phone'=>$fields['phone'],
            'password' => bcrypt($fields['password'])
        ]);
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response,201);
    }
    public function login(Request $request){
        $fields = $request->validate([
            'name'=>'required|string',
            'email'=>'required|string',
            'password'=>'required|string',
            'phone'=>'required|digits:11'
        ]);
        $user = User::where('email',$fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password))
        {
            return response([
                'message' => 'wrong'
            ],401);
        }
        $token = $user->createToken('my-app-token')->plainTextToken;
        $response = [
            'user' => $user,
            'token' => $token
        ];
        return response($response,201);
    }
    public function logout(Request $request){
        $request->user()->tokens()->delete();

        return [
            'message'=>"logged out",
        ];

    }
}
