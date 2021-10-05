<?php

namespace App\Http\Controllers\PictureApis;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\UserPicture;

class PictureRejection extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    function PictureRejection(Request $request){
            
            $user = auth()->user();
            $user_type = $user->is_admin;
            if($user_type == 1){

            $picture_id = $request->picture_id;
            $pic = UserPicture::find($picture_id);
            $pic->delete();



            return response()->json([
                'status' => true,
                'message' => 'Admin successfully deleted picture',
                'picture' => $pic
            ], 201);

        }
    }
}
