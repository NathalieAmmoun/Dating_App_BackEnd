<?php

namespace App\Http\Controllers\PictureApis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserPicture;

class PictureDelete extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function PictureDelete(Request $request){

        $validator = Validator::make($request->all(), [
            'picture_id'=>'required|integer'

        ]);


        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }

        $picture_id = $request->picture_id;

        $pic = UserPicture::find($picture_id);
        $pic->delete();



        return response()->json([
            'status' => true,
            'message' => 'User successfully approved picture',
            'picture' => $pic
        ], 201);
    }
}
