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
                

            $picture_id = ($request->header('pictureid'));
            $pic = UserPicture::find($picture_id);
            $pic->is_approved = 1;
            $pic->save();



            return response()->json([
                'status' => true,
                'message' => 'Admin successfully approved picture',
                'picture' => $pic
            ], 201);

        }
    }
}
