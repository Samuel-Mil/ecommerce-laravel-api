<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return response([
                "message" => "Email or password are incorrect!"
            ], 401);
        }

        $token = '';
        if($user->isAdmin === true){
            $token = $user->createToken(env('API_TOKEN'))->plainTextToken;
        }


        return response([
            "user"  => $user,
            "token" => $token
        ]);
    }
}
