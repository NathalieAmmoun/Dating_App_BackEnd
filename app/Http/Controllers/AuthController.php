<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserPicture;
use App\Models\UserHobby;
use App\Models\UserInterest;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if (!$token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
            'gender' => 'required|boolean',
            'interested_in' => 'required|boolean',
            'dob' => 'date|before:-18 years|required',
            'height' => 'integer|required',
            'nationality' => 'string|required',
            'bio' => 'string|required'

        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }

        // $user = User::create(array_merge(
        //     $validator->validated(),
        //     ['password' => bcrypt($request->password)]
        // ));

        $user = new User;
        $user->is_admin = false;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->gender = $request->gender;
        $user->interested_in = $request->interested_in;
        $user->dob = $request->dob;
        $user->height = $request->height;
        $user->nationality = $request->nationality;
        $user->bio = $request->bio;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile()
    {
        $user_id=auth()->user()->id;
        $result = AuthController::displayFullProfile($user_id);
        return $result;
    }

    //Display potential matches Based on their gender and what gender they are interested in and 
    public function display(){
        $gender = auth()->user()->gender;
        $interested_in = auth()->user()->interested_in;
      
        $users_array = array();
        $users_array = User::where('gender', $interested_in)
                        ->where('interested_in', $gender)
                        ->get();
        $id = 0;
        $pictures_array = array();
        for($i = 0; $i < count($users_array); $i++){
            $id = $users_array[$i]->id;
            $pictures_array[$i]= UserPicture::where('user_id',$id)
                                            ->where('is_approved', '1')
                                            ->get("id","picture_url");
        }

        $result = array();
        for($j = 0; $j < count($users_array); $j++){
            $result[$j]["user"] = $users_array[$j];
            $result[$j]["pictures"] = $pictures_array[$j];
        }

        return json_encode($result ,JSON_PRETTY_PRINT);
    }
    public function displayFullProfile($id){
        $user = array();
        $user = User::find($id);
        $pictures = array();
        $pictures = UserPicture::where('user_id',$id)
                                ->where('is_approved', '1')
                                ->get(["id","picture_url"]);
        $hobbies_array=array();
        $hobbies_array = UserHobby::where('user_id',$id)
                                    ->get(["id","name"]);
        $interests_array=array();
        $interests_array = UserInterest::where('user_id',$id)
                                    ->get(["id","name"]); 
        $result = array();                          
        $result["user"] = $user;
        $result["pictures"]= $pictures;
        $result["hobbies"]= $hobbies_array;
        $result["interests"]= $interests_array;
        return json_encode($result ,JSON_PRETTY_PRINT);
    }
    //Display Full Profile of Specific User After Clicking on his/her Profile to Logged In User
    public function getProfileData(Request $req){
        $user_id = $req->user_id;
        $result = AuthController::displayFullProfile($user_id);
        return $result;
    }

    
    public function editProfileInformation(Request $req){
        $id =auth()->user()->id;
        $user = User::find($id);
        $user->name = $req->name;   
        $user->gender = $req->gender;
        $user->interested_in = $req->interested_in;
        $user->dob = $req->dob;
        $user->height = $req->height;
        $user->nationality = $req->nationality;
        $user->bio = $req->bio;
        $user->save();
        return response()->json(['message' => 'profile successfully updated']);
    }
    public function editHobby(Request $req){
        $hobby_id =$req->hobby_id;
        $hobby = UserHobby::find($hobby_id);
        $hobby->name = $req->name;   
        $hobby->save();
        return response()->json(['message' => 'Hobby successfully updated']);
    }
    public function deleteHobby(Request $req){
        $hobby_id =$req->hobby_id;
        $hobby = UserHobby::where('id', $hobby_id)->delete();
        return response()->json(['message' => 'Hobby successfully deleted',]);
    }
    public function editInterest(Request $req){
        $interest_id =$req->interest_id;
        $interest = UserInterest::find($interest_id);
        $interest->name = $req->name;   
        $interest->save();
        return response()->json(['message' => 'Interest successfully updated']);
    }
    public function deleteInterest(Request $req){
        $interest_id =$req->interest_id;
        $interest = UserInterest::where('id', $interest_id)->delete();
        return response()->json(['message' => 'Interest successfully deleted',]);
    }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }
}
