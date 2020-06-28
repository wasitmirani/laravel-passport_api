<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function  login(Request $request){

                            $request->validate([
                                'email' => 'required|string|email',
                                'password' => 'required|string',
                            ]);
                            $credentials = request(['email', 'password']);
                            if(!Auth::attempt($credentials))
                                return response()->json([
                                    'message' => 'Unauthorized'
                                ], 401);
                            $user = $request->user();
                            $tokenResult = $user->createToken('Personal Access Token');
                            $token = $tokenResult->token;
                            if ($request->remember_me)
                                $token->expires_at = Carbon::now()->addWeeks(1);
                            $token->save();
                            return response()->json([
                                'access_token' => $tokenResult->accessToken,
                                'token_type' => 'Bearer',
                                'expires_at' => Carbon::parse(
                                    $tokenResult->token->expires_at
                                )->toDateTimeString()
                            ]);
    }

    public function user(Request $request)
    {

        return response()->json($request->user());
    }
}
