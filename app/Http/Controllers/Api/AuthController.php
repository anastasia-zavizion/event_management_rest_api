<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $validated =  $request->validate([
           'email'=>'required|email',
           'password'=>'required'
        ]);
        if(Auth::attempt($validated)){
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token'=>$token]);
        }else{
            throw ValidationException::withMessages([
                'email'=>'Incorrect data'
            ]);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logout successfully']);
    }
}
