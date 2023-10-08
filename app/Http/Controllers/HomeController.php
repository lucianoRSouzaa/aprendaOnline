<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct() 
    { 
        $this->middleware('preventBackHistory');
    }

    public function index()
    {
        return view('landing');
    }
}
