<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function home()
    {   
        $taskList = DB::table('tbl_task')
        ->where("status",'!=',"d")
        ->get();

        return view('home',compact('taskList'));
    }

    public function index()
    {   
        $taskList = DB::table('tbl_task')
        ->where("status",'!=',"d")
        ->get();

        return view('dashboard',compact('taskList'));
    }
}
