<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Admins;
use App\Models\Downloads;


class loginController extends Controller
{
   public function login(Request $request){
    $request->validate([
        'email'=>'required|email',
        'password'=>'required'
    ]);

    $email = $request['email'];
    $pass = $request['password'];

    // setup mysql connection
    $host="localhost";
    $dbuser=env('DB_USERNAME');
    $dbpass=env('DB_PASSWORD');
    $db=env('DB_DATABASE');

    $conn = mysqli_connect($host,$dbuser,$dbpass,$db);

    // filtering user inputs
    $email = mysqli_real_escape_string($conn,$email);
    $email = htmlspecialchars($email);
    $pass = mysqli_real_escape_string($conn,$pass);
    $pass = htmlspecialchars($pass);

    $num = DB::table('admins')
    ->where('email','=',$email)
    ->where('passwd','=',$pass)
    ->get()->count();

    if($num==1){
        // Store a piece of data in the session...
    session(['adminmail' => $email]);

    // $rec = Downloads::orderBy('enrollno', 'DESC')->get();

    // return view('admin/dashboard',['rec'=>$rec]);
    return redirect('admin_dashboard');
    }
    else{
        return redirect('/admin');
    }

   }

   public function dashboard(Request $request){
    if(session()->get('adminmail')){
        $rec = Downloads::orderBy('enrollno', 'DESC')->get();

        $count = Downloads::orderBy('enrollno', 'DESC')->get()->count();

    return view('admin/dashboard',['rec'=>$rec,'count'=>$count]);
    }else{
        return view('/admin/login');
    }

   }
}
