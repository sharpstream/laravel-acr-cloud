<?php

namespace SharpStream\AcrCloud;

/**
 * Class ExceptionCode
 * @package SharpStream\AcrCloud
 * @author Frank Clark <frank.clark@sharp-stream.com>
 * See : https://github.com/acrcloud/acrcloud_sdk_php/tree/master/linux/x86-64/php72
 */
class ExceptionCode
{
    public static $NO_RESULT = 1000;
    public static $JSON_ERROR = 2002;
    public static $HTTP_ERROR = 3000;
    public static $GEN_FP_ERROR = 2004;
    public static $UNKNOW_ERROR = 2010;

    private static $code_msg_map = array(
        1000 => "No Result",
        2002 => "Json Error",
        3000 => "Http Error",
        2004 => "gen fingerprint error",
        2010 => 'unknow error'
    );

    public static function getCodeResult($code)
    {
        $tmp = array(
            'status' => array(
                'msg' => self::$code_msg_map[$code],
                'code' => $code,
                'version' => '1.0'
            )
        );
        return json_encode($tmp);
    }

    public static function getCodeResultMsg($code, $msg)
    {
        $tmp = array(
            'status' => array(
                'msg' => self::$code_msg_map[$code] . ":" . $msg,
                'code'=>$code,
                'version'=>'1.0'
            )
        );
        return json_encode($tmp);
    }
}
