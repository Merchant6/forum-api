<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResgisterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
                    'token' => $token['name'] 
                ], 200);
            }
            else
            {
                return response()->json([
                    'error' => 'Unfortunately, there was an error resgistering your credentials. Our team has been notified and is working to resolve the issue. Please try again later.'
                ], 500);
            } 
        }
        catch(\Exception $e)
        {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
