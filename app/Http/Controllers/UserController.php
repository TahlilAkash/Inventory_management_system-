<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function UserRegistration(Request $request)
    {
        try {
            User::create(
                [
                    'firstName' => $request->input('firstName'),
                    'lastName' => $request->input('lastName'),
                    'email' => $request->input('email'),
                    'mobile' => $request->input('mobile'),
                    'password' => $request->input('password'),

                ]
            );
            return response()->json([
                'status' => "success",
                'message' => "user created successfully",
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => "failed",
                'message' => "user creation failed",
            ]);
        }
    }
}
