<?php

use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\OrganizationController;
use App\Http\Controllers\Api\UserAuthController;
use Illuminate\Http\Request;
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


//API login and logout
Route::post('login',[UserAuthController::class,'login']);
Route::post('logout',[UserAuthController::class,'logout'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//ORGANIZATION

//Route::get('/organizations', function () {
//    return \App\Http\Resources\OrganizationResource::collection(\App\Models\Organization::all());
//})->middleware('auth:sanctum');
//Route::get('/organization/{id}', function (string $id) {
//    return new \App\Http\Resources\OrganizationResource(\App\Models\Organization::findOrFail($id));
//})->middleware('auth:sanctum');

Route::get('/organizations', [OrganizationController::class, 'index'])
    ->middleware('auth:sanctum');

Route::get('/organizations/{id}', [OrganizationController::class, 'show'])
    ->middleware('auth:sanctum');

//APPLICATION

Route::get('/applications/{id}', [ApplicationController::class, 'show'])
    ->middleware('auth:sanctum');

Route::get('/organizations/{org_id}/applications/{app_id}', [ApplicationController::class, 'showAppFromOrg'])
    ->middleware('auth:sanctum');

Route::get('/applications', [ApplicationController::class, 'index'])
    ->middleware('auth:sanctum');

//Route::get('/applications', function () {
//    return \App\Http\Resources\ApplicationResource::collection(\App\Models\Application::all());
//});
//Route::get('/applications/{id}', function (string $id) {
//    return new \App\Http\Resources\ApplicationResource(\App\Models\Application::with(['attributeCollection.attributeLists','scenes.attributeCollection.attributeLists','voices.attributeCollection.attributeLists'])->find($id));
//});

//ATTRIBUTE COLLECTION

Route::get('/attributecollection/{id}', function (string $id) {
    return new \App\Http\Resources\AttributeCollectionResource(\App\Models\AttributeCollection::with(['attributeLists'])->find($id));
});

//ATTRIBUTE LIST

Route::get('/attributelist/{id}', function (string $id) {
    return new \App\Http\Resources\AttributeListResource(\App\Models\AttributeList::findOrFail($id));
});
