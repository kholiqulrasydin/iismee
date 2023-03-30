<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    public function getUserData(){
        $data = User::where('id', Auth::user()->id)->first();
        
        return response()->json([
            'responseData' => [
                'userData' => $data
            ]], 200);
    }

    public function changeProfile(){

    }

}
