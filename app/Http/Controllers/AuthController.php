<?php

namespace App\Http\Controllers;

use App\Http\Utils;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

//use Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $validate = Validator($request->all(),[
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validate->fails()){
            $errors = $validate->errors()->all();
            $errorMessage = 'Error, no se pudo guardar en el sistema, intente nuevamente. '. implode(' , ', $errors);
            return Utils::responseJson(
                Response::HTTP_BAD_REQUEST,
                $errorMessage,
                $validate->errors(),
                Response::HTTP_OK
            );
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auht_token')->plainTextToken;
        return Utils::responseJson(
            Response::HTTP_OK,
            'Registro exitoso',
            ['user' => $user,'access_token' => $token,'token_type' => 'Bearer'],
            Response::HTTP_CREATED
        );
    }

    public function login(Request $request){

        if(!Auth::attempt($request->only('email','password'))){
            return Utils::responseJson(
                Response::HTTP_OK,
                'Error, Unauthorized',
                [],
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('auht_token')->plainTextToken;

        return Utils::responseJson(
            Response::HTTP_OK,
            'Bienvenido '.$user->name,
            ['user' => $user,'access_token' => $token,'token_type' => 'Bearer'],
            Response::HTTP_OK
        );
    }

    public function logout(){
        auth()->user()->tokens()->delete();
        return Utils::responseJson(
            Response::HTTP_OK,
            'Has cerrado session exitosamente',
            [],
            Response::HTTP_OK
        );
    }
}
