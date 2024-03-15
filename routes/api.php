<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\SceneController;
use App\Http\Controllers\Api\VoiceController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Organization;

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

//API login and logout
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//ORGANIZATION
Route::get('/organizations', [OrganizationController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])
    ->middleware('auth:sanctum')
    ->middleware('user.properties');

//APPLICATION
Route::get('/organizations/{organization}/applications/{application}', [ApplicationController::class, 'show'])
    ->scopeBindings()
    ->middleware('auth:sanctum')
    ->middleware('user.properties');

Route::get('/organizations/{organization}/applications', [ApplicationController::class, 'index'])
    ->middleware('auth:sanctum')
    ->middleware('user.properties');

//SCENE
Route::get('/organizations/{organization}/applications/{application}/scenes/{scene}', [SceneController::class, 'show'])
    ->scopeBindings()
    ->middleware('auth:sanctum')
    ->middleware('user.properties');

//VOICE
Route::get('/organizations/{organization}/applications/{application}/voices/{voice}', [VoiceController::class, 'show'])
    ->scopeBindings()
    ->middleware('auth:sanctum','user.properties');


//CHAT COMPLETIONS
Route::post('/organizations/{organization}/applications/{application}/scenes/{scene}/chat/completions',
    [\App\Http\Controllers\Api\OpenAiController::class,'chatCompletionRequest'])
    ->scopeBindings()
    ->middleware('auth:sanctum','user.properties','billable.properties');
