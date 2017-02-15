<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests;
use App\Job;
use App\Curl;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function home(){
        $category = Job::distinct()->get(['occupational_category AS category']);

        return view('welcome',compact('category'));
    }

    public function index(Request $request) {
        $where = $request["where"];
        
        $jobs = Job::where(function($q) use ($where){
            foreach($where as $key => $value){
                if(!strlen($value)) continue;
                $q->where($key, '=', $value);
            }
        })->get();

        $geocodes=array();

        foreach ($jobs as $job) {
            $geo = DB::table('geocodes')->where('id', '=', $job->id)->get();
            
            if(!empty($geo)) {
                $geocodes[$geo[0]->id] = array('lat'=>$geo[0]->lat, 'lng'=>$geo[0]->lng);
            }
        }
        
        return compact('jobs','geocodes');
    }
}