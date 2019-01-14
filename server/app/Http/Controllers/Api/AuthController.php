<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['OK'], Response::HTTP_OK);
    }

    public function user()
    {
        return response()->json([ 'user' => auth()->user()], Response::HTTP_OK);
    }
}
