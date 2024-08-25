<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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

    public function UserLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))
            ->where('password', '=', $request->input('password'))
            ->count();

        if ($count == 1) {
            //user login -> JWT token issue 
            $token = JWTToken::CreateToken($request->input('email'));

            return response()->json([
                'status' => "success",
                'message' => "user login successfully",
                'token' => $token,
            ]);
        } else {
            return response()->json([
                'status' => "failed",
                'message' => "user login failed",
            ]);
        }
    }

    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');
        $otp = rand(1000, 9999);
        $count = User::where('email', '=', $email)->count();

        if ($count == 1) {
            //send otp to email address
            Mail::to($email)->send(new OTPMail($otp));

            //inset that otp in database user table...
            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => "success",
                'message' => "otp send successfully",
            ]);

        } else {
            return response()->json([
                'status' => "failed",
                'message' => "user not found",
            ]);
        }
    }

    public function VerifyOTP(Request $request)
    {
        $email=$request->input('email');
        $otp=$request->input('otp');
        $count=User::where('email','=',$email)
        ->where('otp','=',$otp)->count();
        if($count==1){
            // Database otp update

            User::where('email','=',$email)->update(['otp'=>'0']);
            //pass reset the token issue
            $token=JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => "success",
                'message' => "otp verification successful",
                'token' => $token,
            ]);
        }else{
            return response()->json([
                'status' => "failed",
                'message' => "unauthorized",
            ]);

        }
    }

    public function ResetPassword(Request $request){
        try{
            $email=$request->header('email');
            $password=$request->input('password');
            User::where('email','=',$email)->update(['password'=>$password]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ]);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ]);
        }
    }
}
