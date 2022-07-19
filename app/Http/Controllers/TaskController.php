<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function add_task(Request $request)
    {   
        $insert_array = array(
            "task_name"    => $request->task_name,
            "task_details" => $request->task_details,
            "status"       => 'a',
            "createddate"  => date('Y-m-d H:i:s'),
        );

        $id = DB::table('tbl_task')->insertGetId($insert_array);
        
        return redirect("home")->withSuccess('Created Sucessfully');

    }
    public function check_task(Request $request)
    {   
        $user_id = Auth::user()->id;

        $update_array = array(
            "user_id"      => $user_id,
            "start_time"   => date('Y-m-d H:i:s'),
        );

        DB::table('tbl_task')
        ->where('id', $request->task_id)
        ->update($update_array);

        return response()->json(["status" => 1]);

    }

    public function end_task(Request $request)
    {   
        $user_id = Auth::user()->id;

        $update_array = array(
            "end_time"     => date('Y-m-d H:i:s'),
            "status"       => 'c',
        );

        DB::table('tbl_task')
        ->where('id', $request->task_id)
        ->update($update_array);

        return response()->json(["status" => 1]);
    }

}
