<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
Use App\plans;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $plans=plans::get();
        // return view('home',compact('plans'));
        return view('home');
    }
   
}
