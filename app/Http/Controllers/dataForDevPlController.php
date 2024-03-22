<?php

namespace App\Http\Controllers;

use App\Models\pages;
use App\Models\DataForDevPl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class dataForDevPlController extends Controller
{
    // TODO: Get data
    public function index(Request $request){
        // TODO: Search for data
        $data = DataForDevPl::where('access_token', $request->access_token)
        ->where('id_user' , $request->id)
        ->where('id_project', Crypt::decryptString($request->id_project))
        ->where('id_page', Crypt::decryptString($request->id_page))
        ->where('language_name', $request->language_name)
        ->where('viewport_name', $request->viewport_name)
        ->get();
        if(count($data) > 0){
            $page = pages::find(Crypt::decryptString($request->id_page));
            $data_json = Storage::json($page->filePath);
            return response()->json($data_json);
        }else{
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }
    }
    // TODO: Get pages
    public function getPages($id,$id_project){
        // TODO: Check if we shared with him all page or not
        $pages = DataForDevPl::join('pages', 'pages.id_pages', '=', 'data_for_dev_pl.id_page')
        ->where('data_for_dev_pl.id_project', Crypt::decryptString($id_project))
        ->where('data_for_dev_pl.id_user',$id)
        ->select('pages.id_pages', 'pages.pageName')
        ->get();
        $pages = $pages->unique('pageName');
        foreach ($pages as $key => $value) {
            $pages[$key]['id_pages'] = Crypt::encryptString($value->id_pages);
        }
        return response()->json($pages);
    }
    // TODO: Get language
    public function getLanguage($id, $id_page,$id_project){
        $language = DataForDevPl::select('language_name')
        ->where('id_user', $id)
        ->where('id_page', Crypt::decryptString($id_page))
        ->where('id_project', Crypt::decryptString($id_project))
        ->get();
        $language = $language->unique('language_name');
        return response()->json($language);
    }
    // TODO: Get viewport
    public function getViewPort($id, $language,$id_page){
        $viewport = DataForDevPl::select('viewport_name')
        ->where('id_page', Crypt::decryptString($id_page))
        ->where('id_user', $id)
        ->where('language_name', $language)
        ->get();
        $viewport = $viewport->unique(function ($item){
            return $item['viewport_name'];
        })->values();
        return response()->json($viewport);
    }
    // TODO: Get access token
    public function getAccessToken(Request $request){
        $access_token = DataForDevPl::where('id_user', $request->id)
        ->where('id_project', Crypt::decryptString($request->id_project))
        ->where('id_page', Crypt::decryptString($request->id_page))
        ->where('language_name', $request->language)
        ->where('viewport_name', $request->viewport)
        ->first();
        return response()->json($access_token, 200);
    }
    // FIXME: This code will be deleted after the test
    public function ecryptData(){
        $project_id = Crypt::encryptString('11');
        $page_id = Crypt::encryptString('14');
        return response()->json('http://127.0.0.1:5500/?access_token=3w33ww3r6f6f5e5e88ffggw&project_id='.$project_id.'&page_id='. $page_id .'&language_name=en&viewport_name=Desktop');
    }
}
