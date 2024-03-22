<?php

namespace App\Http\Controllers;

use App\Models\pages;
use App\Models\projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class pagesController extends Controller
{
    // TODO: Change data json
    public function changeDataJson(Request $request){
        // TODO: Get project
        $projects = projects::where('projectName', $request->projectname)->where('id' , $request->id)->first();
        // TODO: Get page
        $pages = pages::where('pageName' , $request->page)->where('id_projects' , $projects->id_projects)->first();
        // TODO: Get json file
        $json_file = Storage::json($pages->filePath);
        $json_data = $json_file;
        $json_data[$request->frame] = $request->data;
        Storage::put($pages->filePath, json_encode($json_data));
    }
    // TODO: get list pages by id of the project
    public function listPage($id){
        $id_project = $id;
        $pages = pages::where('id_projects' , $id_project)->get();
        return response()->json($pages);
    }
    // TODO: Get list of frame in a page
    public function listLanguage($id_page, $id_projects){
        if($id_page !== 'all'){
            $page = pages::find($id_page);
            $path_file = $page->filePath;
            $data_file = [
                "pageName" => $page->pageName,
                "data" => Storage::json($path_file)
            ];
            return response()->json([
                'data_json' => $data_file
            ]);
        }else if($id_page == 'all'){
            Log::info([$id_page, $id_projects]);
            $page = pages::where('id_projects', $id_projects)->get();
            $data_file = [];
            foreach ($page as $value) {
                array_push($data_file, [
                    "pageName" => $value->pageName,
                    "data" => Storage::json($value->filePath)
                ]);
            }
            return response()->json([
                'data_json' => $data_file
            ]);
        }
    }
}
