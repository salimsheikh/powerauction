<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plan;

class TeamController extends Controller
{
    public function index(){
        $plans = Plan::select('id','amount')->where('status', 'publish')->orderBy('order', 'ASC')->get();
        return view('admin.teams',compact('plans'));
    }
}
