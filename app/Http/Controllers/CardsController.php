<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Card;
// use DB;

class CardsController extends Controller
{
    public function index() {
        //DB object is defined in file in root dir, and we must include that file, otherwise
        //laravel will look for DB object in App\Http\Controllers
        $cards = Card::all();
        
        return view('cards.index', compact('cards'));
    }
    
    //by type-hinting we tell laravel to fetch instance of Card based on id passed in url
    public function show(Card $card) {
        return view('cards.show', compact('card'));
    }
}
