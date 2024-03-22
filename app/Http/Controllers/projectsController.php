<?php

namespace App\Http\Controllers;

use App\Models\pages;
use App\Models\projects;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class projectsController extends Controller
{
    // TODO: Store project
    public function store(Request $request){
        // TODO: Cheack if the project is exists
        $projects = projects::where('projectName', $request->projectname)->where('id' , $request->id)->first();
        // TODO: IF not exists we will create it
        if(!isset($projects)){
            $projects = projects::create([
                'projectName' => $request->projectname,
                'id' => $request->id
            ]);
        }
        // TODO: Cheack if the page is exists in database and if not we will create it
        $pages = pages::where('pageName' , $request->page)->where('id_projects' , $projects->id_projects)->first();
        if(!isset($pages)){
            $contents = [
                $request->frame => $request->dataFrame
            ];
            $json_file = Storage::put($projects->projectName . '/'. $request->page. '.json', json_encode($contents));
            $pages = pages::create([
                'pageName' => $request->page,
                'filePath' => $projects->projectName . '/'. $request->page. '.json',
                'id_projects' => $projects->id_projects
            ]);
        }
        // TODO: Cheack if frame exists in file json
        $json_data_file = Storage::get($pages->filePath);
        $json_data_file = json_decode($json_data_file, true);
        if(!isset($json_data_file[$request->frame])){
            $json_data_file[$request->frame] = $request->dataFrame;
            Storage::put($pages->filePath, json_encode($json_data_file));
        }
        return response()->json([
            'status' => 200,
            'dataJson' => $json_data_file
        ]);
    }
    // TODO: Get project by id user
    public function index($id){
        $id = $id;
        $projects = projects::where('id', $id)->get();
        return response()->json([
            "projects" => $projects
        ]);
    }
}
