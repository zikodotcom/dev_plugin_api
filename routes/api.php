<?php

use App\Http\Controllers\dataForDevPlController;
use App\Http\Controllers\pagesController;
use App\Http\Controllers\projectsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// TODO: Login

Route::post('/login', function (Request $request){
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return response()->json([
            'status' => 200,
            'id' => Auth::user()->id
        ]);
    }else{

        return response()->json([
            'status' => 400,
        ]);
    }
});

// TODO: Projects route
Route::prefix('projects')->group(function (){
    Route::post('/', [projectsController::class, 'store']);
    Route::get('/listProjects/{id}', [projectsController::class, 'index']);
});

// TODO: Page route

Route::prefix('pages')->group(function (){
    Route::get('/listPage/{id}', [pagesController::class, 'listPage']);
    Route::get('/listLanguage/{id_page}/{id_projects}', [pagesController::class, 'listLanguage']);
    Route::post('/getDataFrame', [pagesController::class, 'frameData']);
    Route::post('/updateJsonFile', [pagesController::class, 'updateJsonFile']);
});

// TODO: DevPlugin route
Route::prefix('devPlugin')->group(function (){
    // TODO: Get data
    Route::get('/{access_token}/{id_project}/{id_page}/{language_name}/{viewport_name}/{id}', [dataForDevPlController::class, 'index']);
    // TODO: Get pages shared with developer
    Route::get('/getPages/{id}/{id_project}', [dataForDevPlController::class, 'getPages']);
    // TODO: Get language shared with developper
    Route::get('/getLanguage/{id}/{id_page}/{id_project}', [dataForDevPlController::class, 'getLanguage']);
    // TODO: Get viewport shared with developper
    Route::get('/getViewPort/{id}/{language}/{id_page}', [dataForDevPlController::class, 'getViewPort']);
});
// TODO: Get access token
Route::get('/getAccessToken/{id}/{id_project}/{id_page}/{language}/{viewport}', [dataForDevPlController::class, 'getAccessToken']);
Route::get('/crypt', [dataForDevPlController::class, 'ecryptData']);
