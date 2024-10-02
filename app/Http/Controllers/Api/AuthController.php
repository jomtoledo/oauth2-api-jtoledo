<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Laravel\Passport\Client;
use App\Models\User;

class AuthController extends Controller
{

    /**
     * @ApiDescription(section="Auth", description="Register a user")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/api/register")
     * @ApiParams(name="name", type="string", nullable=false, description="User's name")
     * @ApiParams(name="email", type="string", nullable=false, description="User's e-mail address")
     * @ApiParams(name="password", type="string", nullable=false, description="User's password")
     * @ApiReturnHeaders(sample="HTTP 201 OK")
     * @ApiReturn(type="object", sample="{
     *    'id': 'int',
     *    'name': 'string',
     *    'email': 'string',
     *    'created_at': 'timestamp',
     *    'updated_at': 'timestamp'
     * }")
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json($user, 201);
    }

    /**
     * @ApiDescription(section="Auth", description="Login a user")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/api/login")
     * @ApiParams(name="email", type="string", nullable=false, description="User's e-mail address")
     * @ApiParams(name="password", type="string", nullable=false, description="User's password")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *    'token': 'string'
     * }")
     */
    public function login(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to authenticate the user
        $user = User::where('email', $request->email)->first();
        
        if ($user && Hash::check($request->password, $user->password)) {
        //if (Auth::guard('api')->attempt(['email' => $request->email, 'password' => $request->password])) {
           
            // Generate the access token
            $token = $user->createToken('OAuth2 App - Access Token')->accessToken;

            // Return the token as a response
            return response()->json(['token' => $token]);
        }
        
        // Return an error response if authentication fails
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * @ApiDescription(section="Auth", description="Logout a user")
     * @ApiMethod(type="post")
     * @ApiRoute(name="/api/logout")
     * @ApiReturnHeaders(sample="HTTP 200 OK")
     * @ApiReturn(type="object", sample="{
     *   'message': 'Successfully logged out'
     * }")
     */
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->token()->revoke();
            return response()->json(['message' => 'Successfully logged out'], 200);
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }
}