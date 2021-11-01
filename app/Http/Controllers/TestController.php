<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
class Service
{
    //
}
class TestController extends Controller
{
    public function index(Request $request, Service $service){
        echo 123;
    }
}
