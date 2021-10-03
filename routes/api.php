<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PictureApis\PictureUpload;
use App\Http\Controllers\PictureApis\PictureApproval;
use App\Http\Controllers\PictureApis\PictureEdit;
use App\Http\Controllers\PictureApis\PictureDelete;
use App\Http\Controllers\PictureApis\UnapprovedPics;
use App\Http\Controllers\ViewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

 Route::post('/adminLogin', [AuthController::class, 'adminLogin'])->name('admin-login');
 Route::post('/login', [AuthController::class, 'login'])->name('login');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function () {
    //Jamal
    Route::post('picture_upload', [PictureUpload::class, 'pictureUpload']);
    Route::post('PictureApproval', [PictureApproval::class, 'PictureApproval']);
    Route::post('PictureEdit', [PictureEdit::class, 'PictureEdit']);
    Route::post('PictureDelete', [PictureDelete::class, 'PictureDelete']);
    Route::get('UnapprovedPics', [UnapprovedPics::class, 'UnapprovedPics']);


    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    
    //Nathalie
    Route::get('display', [AuthController::class, 'display']);
    Route::post('display-profile-data', [AuthController::class, 'getProfileData']);
    Route::post('edit-profile-information', [AuthController::class, 'editProfileInformation']);
    Route::post('edit-hobby/{hobby_id}', [AuthController::class, 'editHobby']);
    Route::post('delete-hobby/{hobby_id}', [AuthController::class, 'deleteHobby']);
    Route::post('edit-interest/{interest_id}', [AuthController::class, 'editInterest']);
    Route::post('delete-interest/{interest_id}', [AuthController::class, 'deleteInterest']);

    //Waseem
    Route::post('search', [AuthController::class, 'search']);
    Route::post('favorite', [AuthController::class, 'favorite']);
    Route::get('notifications', [AuthController::class, 'getNotifications']);
    Route::get('get-favorites', [AuthController::class, 'getFavorites']);
    Route::get('get-connections', [AuthController::class, 'getConnections']);
});
