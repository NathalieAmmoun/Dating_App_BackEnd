<?php

namespace App\Http\Controllers\PictureApis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserPicture;


class PictureEdit extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function PictureEdit(Request $request){

        $validator = Validator::make($request->all(), [
            'picture_id'=>'required|integer',
            'is_profile_picture'=>'required|boolean'

        ]);


        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
        $user = auth()->user();
        $user_id = $user->id;
        $picture_id = $request->picture_id;
        $PastPP = UserPicture::where('user_id',$user_id)->where('is_profile_picture',"1")->first();

        if($PastPP){
        $PastPP->is_profile_picture = 0;
        $PastPP->save();
        }

        $pic = UserPicture::find($picture_id);
        $pic->is_profile_picture = 1;
        $pic->save();



        return response()->json([
            'status' => true,
            'message' => 'User successfully approved picture',
            'picture' => $pic
        ], 201);
    }
}
