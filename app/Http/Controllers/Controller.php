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

    protected function BadRequest($validator, $message = "Request didn't pass the validation!"){
        return response()->json([
            "ok" => false,
            "errors" => $validator->errors(),
            "message"=> $message,
        ], 400);
    }
}
