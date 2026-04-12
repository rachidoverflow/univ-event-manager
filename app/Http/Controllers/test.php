<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class test extends Controller
{
    function welcome(Request $request){
    return abort(404);
   }
}
