<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Files;
use App\Models\Downloadlimit;
use App\Models\Downloads;
use Illuminate\Support\Facades\Storage;
use DB;

class fileHandler extends Controller
{
    public function files(Request $request){

        if(session()->get('adminmail')){

            $topic = $request['topic'];
         $file = $request['formFile'];

         $num = DB::table('files')
         ->where('topic','like','%'.$topic.'%')
         ->get()->count();

         if($num==1){
            return view('exists');
         }
         else{
            $file = new Files;
            $file->topic = $topic;

        $public_des_path='public/files';
        $fname = $request->file('formFile');
        $file->filename =  $fname = $request->file('formFile')->store('');
        $request->file('formFile')->storeAs($public_des_path,$fname);
        $request->file('formFile')->move('files/',$fname);

        $file->date = date("Y/m/d");
        date_default_timezone_set("Asia/Kolkata");
        $file->time = date("h:i:sa");
        $file->save();

            return redirect('/admin_dashboard');
         }

        }else{
            return view('/admin/login');
        }
    }

    public function deleteFiles(Request $request){
        if(session()->get('adminmail')){
            $request->validate([
                'topicid'=>'required',
                'filelink'=>'required'
            ]);

            $id = $request['topicid'];
            $file = $request['filelink'];

            if(\File::exists(public_path('files/'.$file))){
                \File::delete(public_path('files/'.$file));
                echo "File deleted!";

                }else{
                dd('File does not exists.');
                }

            unlink(storage_path('app/'.$file));
            unlink(storage_path('app/public/files/'.$file));


                $file = Files::find($id);
                $file->delete();

                return redirect('/admin_links');

        }else{
            return view('/admin/login');
        }
    }

    public function fileModify(Request $request){
        if(session()->get('adminmail')){

            $request->validate([
                'topicid'=>'required',
                'topicname'=>'required',
                'filelink'=>'required'
            ]);

            $id = $request['topicid'];
            $topic = $request['topicname'];
            $fileName = $request['filelink'];

            return view('/admin/modify',['id'=>$id,'name'=>$topic,'filename'=>$fileName]);

        }else{
            return view('/admin/login');
        }
    }

    public function fileModifyByTopicName(Request $request){
        if(session()->get('adminmail')){

            $request->validate([ 'topicName'=>'required' ]);

            $topic = $request['topicName'];

            // $num = DB::table('files')->where('topic','like','%'.$topic.'%')
            // ->get()->count();

            $num = DB::table('files')->where('topic',$topic)
            ->get()->count();

            if($num==1){
                $id = Files::select('id')->where('topic','like','%'.$topic.'%')
            ->first()->id;

            $fileName = Files::select('filename')->where('id','=',$id)
            ->first()->filename;

            return view('/admin/modify',['id'=>$id,'name'=>$topic,'filename'=>$fileName]);

        }
            else{
                return "NO RECORDS";
            }
        }else{
            return view('/admin/login');
        }
    }

    public function fileModifyRequest(Request $request){
        if(session()->get('adminmail')){

            $request->validate([
                'topicid'=>'required',
                'topic'=>'required',
                'formFile'=>'required',
                'filename'=>'required'
            ]);

            $id = $request['topicid'];
            $topic = $request['topic'];
            $newFile = $request['formFile'];
            $oldFile = $request['filename'];

            //  file deletion code
            if(\File::exists(public_path('files/'.$oldFile))){
            \File::delete(public_path('files/'.$oldFile));
             echo "File deleted!";
             }else{
            dd('File does not exists.');
            }

            unlink(storage_path('app/'.$oldFile));
            unlink(storage_path('app/public/files/'.$oldFile));

            // modifying record into database
                $file = Files::find($id);
                $file->topic = $topic;
                // storing image
                $public_des_path='public/files';
            $fname = $request->file('formFile');
            $file->filename =  $fname = $request->file('formFile')->store('');
            $request->file('formFile')->storeAs($public_des_path,$fname);
            $request->file('formFile')->move('files/',$fname);

                $file->save();

                return redirect('/admin_links');

        }else{
            return view('/admin/login');
        }
    }

}
