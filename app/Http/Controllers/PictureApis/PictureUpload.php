<?php
namespace App\Http\Controllers\PictureApis;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserPicture;
use Illuminate\Support\Facades\Validator;
class PictureUpload extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    function pictureUpload(Request $request){
        $user = auth()->user();
        $user_id = $user->id;
        $validator = Validator::make($request->all(), [
            'picture' =>'required|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
        $picture_url = $request->file("picture")->store('/public');
        $picture_save = $request->file("picture")->store('');
        $PastPP = UserPicture::where('user_id',$user_id)->where('is_profile_picture',"1")->first();
        if($PastPP){
        $PastPP->is_profile_picture = 0;
        $PastPP->save();
        }
        $pic = new UserPicture;
        $pic->user_id = $user_id;
        $pic->picture_url = 'http://127.0.0.1:8000/storage/'.$picture_save;
        $pic->is_profile_picture = 1;
        $pic->is_approved = 0;
        $pic->save();
        return response()->json([
            'status' => true,
            'message' => 'User successfully added picture',
            'picture' => $pic
        ], 201);
    }
}