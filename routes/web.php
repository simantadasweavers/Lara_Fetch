<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\admin\loginController;
use App\Http\Controllers\admin\fileHandler;
use App\Http\Controllers\admin\linksController;
use App\Http\Controllers\admin\timerController;
use App\Http\Controllers\fileDownloadController;
use App\Http\Controllers\admin\searchSuggestion;

/**
 *  /  for normal index view by user
 *  /file_download -> helps to generate download link for user's
 *  /file_process -> helps to process/generate download link for file..
 *                   also helps to shows Google Ads
 *  /final_download -> proceding user's to download file and also saves the download file
 *                     record nto database.
 */

Route::get('/', function () {
    return view('index');
});
Route::get('/file_download/{link}/{topic}', function (String $link,String $topic) {
    return view('download',['downloadFilename'=>$link,'topic'=>$topic]);
});
Route::get('/file_process',[fileDownloadController::class,'fileDownload']);
Route::get('/final_download',[fileDownloadController::class,'finalDownload']);



/**
 *   /admin -> checks is admin session active or not
 *   /admin_login -> checks admin is logged-in or not
 *   /admin_dashboard -> checks admin login and redirect admin into dashboard
 *   /admin_file -> checks admin logged-in and redirect into admin/files
 *   /admin_file_upload -> upload files into /public directory
 *   /admin_links -> checks admin logged-in and shows all files,their names, Hashname and generate their link
 *   /admin_logout -> helps to admin logout by forgetting session
 *   /admin_timer ->  redirect into timer page of admin panel if admin logged-in
 *   /admin_timer_req -> sends timer update request
 *   /admin_file_modify -> redirect into file modify page
 *   /admin_file_modify_req -> helps to update file's data
 *   /admin_post_search -> helps to find file id and other details by it's topic name
 */

// admin routes adminmail
Route::get('/admin',function(){
    if(session()->get('adminmail')){
        return redirect('/admin_dashboard');
    }else{
        return view('/admin/login');
    }
});
Route::post('/admin_login',[loginController::class,'login']);
Route::get('/admin_dashboard',[loginController::class,'dashboard']);

Route::get('/admin_file',function(){
    if(session()->get('adminmail')){
        return view('/admin/files');
    }else{
        return view('/admin/login');
    }
});
Route::post('/admin_file_upload',[fileHandler::class,'files']);
Route::get('/admin_links',[linksController::class,'link']);
Route::get('/admin_logout',function(){
    if(session()->get('adminmail')){

        session()->forget('adminmail');
        session()->flush();

        return redirect('/admin');

    }else{
        return view('/admin/login');
    }
});
Route::get('/admin_timer',[timerController::class,'redirecttimer']);
Route::post('/admin_timer_req',[timerController::class,'time']);
Route::post('/admin_file_modify',[fileHandler::class,'fileModify']);
Route::post('/admin_file_modify_req',[fileHandler::class,'fileModifyRequest']);
Route::post('/copylink',[linksController::class,'copylink']);
Route::post('/admin_post_search',[fileHandler::class,'fileModifyByTopicName']);
Route::post('/deletefile',[fileHandler::class,'deleteFiles']);
Route::post('/search_sugg',[searchSuggestion::class,'search'])->name('searchsuggestion');
