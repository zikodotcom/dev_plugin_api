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
            $json_data = Http::get($path_file);
            $data_file = [
                "pageName" => $page->pageName,
                "data" => json_decode($json_data)
            ];
            return response()->json([
                'data_json' => $data_file
            ]);
        }else if($id_page == 'all'){
            $page = pages::where('id_projects', $id_projects)->get();
            $data_file = [];
            foreach ($page as $value) {
                $json_data = Http::get($value->filePath);
                array_push($data_file, [
                    "pageName" => $value->pageName,
                    "data" => json_decode($json_data)
                ]);
            }
            return response()->json([
                'data_json' => $data_file
            ]);
        }
    }
}
