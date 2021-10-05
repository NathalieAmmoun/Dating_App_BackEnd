<?php

namespace App\Http\Controllers\PictureApis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserPicture;

class UnapprovedPics extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    function UnapprovedPics(){
        $user = auth()->user();
        $user_type = $user->is_admin;
        if($user_type == 1){
            $pic = UserPicture::all()->where('is_approved','0');
            return response()->json($pic);
        }
    }
}