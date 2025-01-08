<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsersTestController extends Controller
{
    public function register(Request $request)
    {

        $validacion = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if($validacion->fails()){
            return response()->json($validacion->errors(), 400);
        }else{

            DB::table('users')->insert([
                'name' => $request->name,
                'emails' => $request->email,
            ]);

            return response()->json(['message' => 'Usuario registrado'], 201);
        }

    }
}
