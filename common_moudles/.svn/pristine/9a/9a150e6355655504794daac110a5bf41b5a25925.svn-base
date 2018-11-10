<?php
/**
 * Created by PhpStorm.
 * User: caijia
 * Date: 18/1/18
 * Time: 下午5:15
 */

namespace CommonMoudle\Helper;

use CommonMoudle\Constant\CommonConstant;
use Firebase\JWT\JWT;


class JWTHelper
{
	public static function genJWT($customPayload, $expireSeconds = 1800, $secretKey=CommonConstant::JWT_KEY, $alg = 'HS256')
	{
	    $defaultLoad = array(
            CommonConstant::IAT => time(),
            CommonConstant::EXP => time() + $expireSeconds,
        );

	    $payload = array_merge($defaultLoad, $customPayload);
		return JWT::encode($payload, $secretKey, $alg);
	}

	public static function validJWT($token, $key=CommonConstant::JWT_KEY)
	{
		try
		{
			$decoded = JWT::decode($token, $key, array('HS256'));
		}
		catch (\Exception $e)
		{
			return false;
		}
		return (array) $decoded;
	}
}