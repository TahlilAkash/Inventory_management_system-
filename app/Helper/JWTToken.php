<?php

namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    // user login er jnno jwt token create korlam
    public static function CreateToken($userEmail,$usrID):string
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 * 60,
            'userEmail' => $userEmail,
            'userID'=>$usrID

        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    
    public static function VerifyToken($token):string|object
    {
       
        
        try {
            if ($token===null) {
                
                return 'unauthorized';
            }
            else{
                $key =env('JWT_KEY');
                $decode=JWT::decode($token,new Key($key,'HS256'));
                return $decode;
            }
        }
        catch (Exception $e){
            return 'unauthorized';
        }
    }
    // user login er jnno jwt token create done
    // --------------------------------------------------
    
    //verify otp
    public static function CreateTokenForSetPassword($userEmail): string | object
    {
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token',
            'iat' => time(),
            'exp' => time() + 60 *20,
            'userEmail' => $userEmail,
            'userID'=>'0'

        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
