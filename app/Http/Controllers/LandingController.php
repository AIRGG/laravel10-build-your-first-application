<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    //
    public function index(Request $request)
    {
        # code...
        return view('index');
    }
}
