<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Downloadlimit;
use App\Models\Downloads;

class fileDownloadController extends Controller
{
    public function fileDownload(Request $request){
        $request->validate([
            'filename'=>'required',
        'topic'=>'required']);

        $file = $request['filename'];
        $topic = $request['topic'];

        $sec = Downloadlimit::select('downloadsec')
        ->orderBy('id', 'DESC')->first()->downloadsec;

        return view('process',['filename'=>$file,'sec'=>$sec,'topic'=>$topic]);
    }

    public function finalDownload(Request $request){
        $request->validate([ 'fileName' => 'required', 'topic'=>'required','emailbox'=>'required' ]);
        $fileName = $request['fileName'];
        $topic = $request['topic'];
        $email = $request['emailbox'];

        $download = new Downloads;
        $download->name = $topic;
        $download->email = $email;
        $download->date = date("Y/m/d");
        date_default_timezone_set("Asia/Kolkata");
        $download->time = date("h:i:sa");
        $download->loc = $_SERVER['REMOTE_ADDR'];
        $download->save();


        return view('finalDownload',['file'=>$fileName,'topic'=>$topic]);
    }


}
