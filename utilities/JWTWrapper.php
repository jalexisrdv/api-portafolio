<?php

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

class JWTWrapper {

    private const key = "3m18LH6Uqlejp2HiMH0xh_xnA80TWZZD9AJUGeqwt2I5A_Slzss7hajN0cm76khS8iMmIPGhRw5v1Vp4rfPydwtSSasgExVVIaSVDBhoLJorFO646HthQR4VikgS3kg7mTdixAx5A_yOo7xsiZi8A9NQ3OZua_cTegcd327lzFc5EkaVFuBtMlTnmF-2RxSu38iGftd1S5CbJ4Hj-mzN0Dd9EURcl44T62cQBl_IXicIhZI2vm2uFqS_jaqU9ggAAr81TO-E5Ct3pKyg6vo7HGk9NK02mMZIugVzTwRsAMEbBJAKRCPxSo-DahibiyLn6wpUGujlSmzsbQzUacS_Qw";

    static function encode($expiration = 0) {
        $payload = self::createPayload($expiration);
        $jwt = JWT::encode($payload, self::key);
        return $jwt;
    }

    static function decode($jwt) {
        try {
            $payload = JWT::decode($jwt, self::key, array('HS256'));
            return $payload;
        }catch(Exception $e) {
            return null;
        }
    }

    private static function createPayload($time) {
        $issuer = 'https://portafolio.jardvcode.com/';
        if($time === 0) {
            $token = array(
                "iss" => $issuer
            );
            return $token;
        }
        $issued_at = time();
        $expiration_time = $issued_at + $time;
        $token = array(
            "iat" => $issued_at,
            "exp" => $expiration_time,
            "iss" => $issuer
        );
        return $token;
    }

}