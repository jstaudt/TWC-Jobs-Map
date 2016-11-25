<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    public function home(){
        $test = ['Me', 'Ramses'];
        //return view('welcome')->withTest($test);
        return view('welcome',compact('test'));
    }
    
    public function about(){
        return view('about');
    }
}
