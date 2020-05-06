<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('checkUser');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function checkUser(){
        $roles = [];
        if(Auth::check()){
            foreach (auth()->user()->roles as $rol){
                $roles[] = $rol->name;
            }
        }
        return [
            'iniciado'=>Auth::check(),
            'name' => Auth::check()?auth()->user()->name:'',
            'roles' => $roles,
        ];
    }
}
