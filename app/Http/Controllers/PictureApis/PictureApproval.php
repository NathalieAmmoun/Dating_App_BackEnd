<?php

namespace App\Http\Controllers\PictureApis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserPicture;

class PictureApproval extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    function PictureApproval(Request $request){
        $user = auth()->user();
        $user_type = $user->is_admin;
        if($user_type == 1){

            $validator = Validator::make($request->all(), [
                'picture_id'=>'required|integer',
                'is_approved'=>'required|boolean'

            ]);


            if ($validator->fails()) {
                return response()->json(array(
                    "status" => false,
                    "errors" => $validator->errors()
                ), 400);
            }
            $picture_id = $request->picture_id;
            $approval_status = $request->is_approved;
            if($approval_status == 1){
            $pic = UserPicture::find($picture_id);
            $pic->is_approved = $request->is_approved;
            $pic->save();



            return response()->json([
                'status' => true,
                'message' => 'User successfully approved picture',
                'picture' => $pic
            ], 201);

            }else{
                $pic = UserPicture::find($picture_id);
                $pic->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'User successfully rejected picture',
                    'picture' => $pic
                ], 201);
            }
        }else{
            return response()->json(array(
                "status" => false,
                'message' => 'User Unauthenticated',
            ), 400);
        }

    }
}
