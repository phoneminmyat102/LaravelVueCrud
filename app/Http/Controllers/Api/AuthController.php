<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $validated = $request->validated();

            $credentials = [
                'email' => $validated["email"],
                'password' => $validated["password"]
            ];

            if(Auth::attempt($credentials)) {
                $success = true;
                $message = "User logged in Successfully";

            } else {
                $success = false;
                $message = "User email or password went wrong!";
            }
        } catch (\Exception $error) {
            logger()->error($error);
            $success = false;
            $message = "Internal Server error!";
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }

    public function logout() {
        try {
            Session::flush();
            $success = true;
            $message = "Logout Successes!";
        } catch (\Illuminate\Database\QueryException $error) {
            logger()->error($error);
            $success = false;
            $message = $error->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
