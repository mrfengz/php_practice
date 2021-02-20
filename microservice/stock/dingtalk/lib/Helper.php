<?php
/**
 * Created by PhpStorm.
 * User: august
 * Date: 2021/2/4
 * Time: 16:14
 */

class Helper
{
    public static function getToken($salt = '')
    {
        $randomBytes = ['A', 's', 'd', 'G', '$', '@', '*', '='];
        $str = $salt . mt_rand(1, 9999) .time() . join('#', array_rand($randomBytes, mt_rand(2, 5)));
        return substr(md5($str), 8, 16);
    }


    private static $returnJson = false;
    public static function setReturnType($type = 'json')
    {
        if ($type == 'json') self::$returnJson = 'JSON';
    }

    public static function returnData($message = 'OK', $code = 0, $data = [])
    {
        $returnData = [
            'code' => $code,
            'message' => $message,
            'data' => $data
        ];
        print_r($returnData);die;
        if (self::$returnJson == 'JSON') {
            header("Content-type:application/json;charset=utf-8");
            return json_encode($returnData, JSON_UNESCAPED_UNICODE);
        }
        return $returnData;
    }
}