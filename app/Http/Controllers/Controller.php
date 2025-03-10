<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function Ok($data, $message = "Ok!", $others = null){
        return response()->json([
            "ok" => true,
            "data" => $data,
            "message"=> $message,
            "others" => $others
        ]);
    }
}
