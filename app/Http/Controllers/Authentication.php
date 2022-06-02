<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class Authentication extends Controller
{
    public function login(Request $req)
    {
        $userData = User::where('g_id', $req['g_id'])->first();
        if(empty($userData)){
            return response()->json(['responseData' => 'user is not defined'], 401);
        }
        Auth::attempt(['email' => $userData->email, 'password' => $userData->password]);
        $user = Auth::user(); 
        $token =  $user->createToken('iismee')-> accessToken; 
        return response()->json([
            'responseData' => [
                'accessToken' => $token, 
                'userData' => $userData
            ]], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'g_id' => 'required',
            'num' => 'required',
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token =  $user->createToken('iismee')->accessToken;
   
        $userData = User::where('g_id', $request['g_id'])->first();
        return response()->json([
            'responseData' => [
                'msg' => 'User registered successfully',
                'accessToken' => $token, 
                'userData' => $userData
            ]], 200);
    }
}
