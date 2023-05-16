<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class UserAuthController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(array $credentials)
    {
        return User::create([
            'username' => $credentials['username'],
            'email' => $credentials['email'],
            'password' => bcrypt($credentials['password'])
        ]);
    }

    /**
     * Summary of register
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function register(Request $request) : Response
    {
        try
        {
            $credentials = $request->all();
            $val = Validator::make($credentials, [
                'username' => ['required', 'string', 'min:4', 'unique:users'],
                'email' => ['required', 'string', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'max:32'],
                'confirm_password' => ['required', 'string', 'same:password'] 
            ]);

            if(!$val->fails())
            {
                $user = $this->create($credentials);
                $tokenName = $credentials['username']."_auth_register_token";
                $token = $user->createToken($tokenName)->accessToken;
                return response()->json([
                    'success' => 'You have registered, head to the login to access the forum.',
                    'token' => $token 
                ], 200);
            }
            
            return response()->json([
                'error' => 'Unfortunately, there was an error resgistering your credentials. Our team has been notified and is working to resolve the issue. Please try again later.'
            ], 500);
            
        }
        catch(\Exception $e)
        {
            return response()->json([
                'error' => 'Something went wrong.'
            ], 500);
        }
    }

    /**
     * Summary of login
     * @param \Illuminate\Http\Request $request
     * 
     */
    public function login(Request $request)
    {
        
            $request->validate([
                'username' => ['required', 'string', 'min:4'],
                'password' =>  ['required', 'string', 'min:8', 'max:32'],
            ]);
    
            $data = $request->only(['username', 'password']);
            // $user = Auth::user();
    
            if (Auth::attempt($data)) {
    
                $authenticated_user = Auth::user();
                $user = User::find($authenticated_user->id);
    
                $tokenName = $request['username']."_auth_login_token_";
                $token = Auth::user()->createToken($tokenName)->accessToken;
    
                return response()->json(['token' => $token], 200);
            } 
        
       
        return response()->json(['error' => 'Unauthorised'], 401);  
    }

    public function msg()
    {
        return 'You are logged in';
    }
}
