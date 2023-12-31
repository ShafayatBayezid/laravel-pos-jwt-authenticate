<?php

namespace App\Http\Controllers;
// namespace App\Models\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller {
    public function register( Request $request ) {
        $validatedData = $request->validate( [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:4',
            'phone'    => 'required|string|min:11|max:20',
        ] );

        $user = User::create( [
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make( $validatedData['password'] ),
            'phone'    => $validatedData['phone'],
        ] );

        $token = JWTAuth::fromUser( $user );

        return response()->json( compact( 'user', 'token' ), 201 );
    }

    public function login( Request $request ) {
        $credentials = $request->only( 'email', 'password' );

        if ( !$token = Auth::guard( 'jwt' )->attempt( $credentials ) ) {
            return response()->json( ['error' => 'Unauthorized'], 401 );
        }

        return $this->respondWithToken( $token );
    }

    protected function respondWithToken( $token ) {
        return response()->json( [
            'token'      => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60 * 60,
        ] );
    }
}