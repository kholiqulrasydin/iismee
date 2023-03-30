<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Laravel\Socialite\Facades\Socialite;

class Authentication extends Controller
{

    public function signInWithGoogleRedirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function signInWithGoogleCallback()
    {
        $googleAccount = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'g_id' => $googleAccount->id,
        ], [
            // 'name' => $googleAccount->name,
            'email' => $googleAccount->email,
            'google_token' => $googleAccount->token,
            'google_refresh_token' => $googleAccount->refreshToken,
        ]);

        $authedUser = Auth::login($user);

        $userData = User::where('email', Auth::user()->email)->first();

        $token =  $authedUser->createToken('iismee')->accessToken;
        return response()->json([
            'responseData' => [
                'accessToken' => $token,
                'userData' => $userData
            ],
            'statusCode' => 200
        ], 200);
    }

    public function login(Request $req)
    {
        $userData = User::where('email', $req['email'])->first();
        // return response()->json([
        //     'responseData' => [
        //         // 'accessToken' => $token, 
        //         'userData' => $userData
        //     ]], 200);
        if (empty($userData)) {
            return response()->json(['responseData' => 'user is not defined'], 401);
        }
        if(empty($userData['g_id'])){
            User::where('email', $req['email'])->update(['g_id' => $req['g_id']]);
        }

        if(isset($userData['g_id']) && $userData['g_id'] != $req['g_id']){
            return response()->json(['responseData' => 'wrong credential'], 401);
        }
        // if (Auth::attempt(['email' => $userData->email, 'password' => $this->decryptThis($userData->p_alt)])) {
            if (Auth::attempt(['email' => $userData->email, 'password' => $userData->p_alt])) {
            $user = Auth::user();
            $token =  $user->createToken('iismee')->accessToken;
            return response()->json([
                'responseData' => [
                    'accessToken' => $token,
                    'userData' => $userData
                ],
                'statusCode' => 200
            ], 200);
        }
        return response()->json([
            'responseData' => [
                'status' => 'failed to initialize user'
            ],
            'statusCode' => 401
        ], 401);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'g_id' => 'required',
            'num' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['p_alt'] = $input['password'];
        // return $this->encryptThis($input['password']);
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $token =  $user->createToken('iismee')->accessToken;

        $userData = User::where('g_id', $request['g_id'])->first();
        return response()->json([
            'responseData' => [
                'msg' => 'User registered successfully',
                'accessToken' => $token,
                'userData' => $userData
            ],
            'statusCode' => 200
        ], 200);
    }

    public function forgotPassword()
    {
    }

    public function deactivateUser()
    {
    }

    private function encryptThis($string)
    {
        $secretKey = 'Nice Key';
        return base64_encode($secretKey . '_' . $string);
    }

    private function decryptThis($string)
    {
        $secretKey = 'Nice Key';
        $decodedString = base64_decode($string);
        $explodedString = explode('_', $decodedString);
        if ($explodedString[0] == $secretKey) {
            return $explodedString[1];
        }
        return false;
    }
}
