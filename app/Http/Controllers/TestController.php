<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    public function controllerMethod(){

        return response()->json([
            'msg' => 'We should return only Json'
        ]);
    }

     public function test()
    {
       return "this is test";
    }
    
}
