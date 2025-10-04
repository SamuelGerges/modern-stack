<?php

namespace App\Helpers;

class ResponsesHelper
{
    public static function returnSuccessMessage($code, $msg = "")
    {
        return response()->json([
            'status' => 'success',
            'code'   => (int)$code,
            'msg'    => $msg
        ])->setStatusCode($code);
    }

    public static function returnData($dataArr, $msg = "", $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'code'   => (int)$code,
            'msg'    => $msg,
            'data'   => $dataArr,
        ])->setStatusCode($code);
    }

    public static function returnError($code, $msg)
    {
        return response()->json([
            'status' => false,
            'code'   => (int)$code,
            'msg'    => $msg
        ])->setStatusCode($code);
    }
}