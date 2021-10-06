<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserPicture;
use App\Models\UserHobby;
use App\Models\UserInterest;
use App\Models\UserFavorite;
use App\Models\UserNotification;
use App\Models\UserConnection;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'adminLogin']]);
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

    public function adminLogin(Request $request)
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
        

         $access_token = $token;
         return view('dashboard')->with('token', $access_token);

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
            'gender' => 'required',
            'interested_in' => 'required',
            'dob' => 'date|before:-18 years|required',

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
        return view('login');
        // return response()->json(['message' => 'User successfully signed out']);
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
        $user_id =auth()->user()->id;
        $gender = auth()->user()->gender;
        $interested_in = auth()->user()->interested_in;
        $favorites_user2_id = UserFavorite::where('user1_id', $user_id)
                            ->pluck('user2_id')
                            ->all();  

        $users_array = array();
        $users_array = User::where('gender', $interested_in)
                        ->where('interested_in', $gender)
                        ->whereNotIn('id', $favorites_user2_id)
                        ->get();
        $id = 0;
        $pictures_array = array();
        for($i = 0; $i < count($users_array); $i++){
            $id = $users_array[$i]->id;
            $pictures_array[$i]= UserPicture::where('user_id',$id)
                                            ->where('is_profile_picture', '1')
                                            ->where('is_approved', '1')
                                            ->get(["id","picture_url"]);
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
                                ->where('is_profile_picture', '1')
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

    public function continueRegistration(Request $request){
        $user_id = auth()->user()->id;

        $user = User::find($user_id);
        $user->nationality = $request->nationality;
        $user->height = $request->height;
        $user->bio = $request->bio;
        $user->save();

        return response()->json(['message' => 'Registration successfully completed']);

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

    public function search(Request $request){
        $user_id = auth()->user()->id;
        $name = $request->name;
        $gender = auth()->user()->gender;
        $interested_in = auth()->user()->interested_in;

        //get IDs of favorites of logged in user;
       $favorites_user2_id = UserFavorite::where('user1_id', $user_id)
                            ->pluck('user2_id')
                            ->all();  

       $users_array = array();
       $users_array = User::where('name', 'LIKE', '%'.$name.'%')
                        ->where('gender', $interested_in)
                        ->where('interested_in', $gender)
                        ->whereNotIn('id', $favorites_user2_id)
                        ->get();

        $id = 0;
        $pictures_array = array();
        for($i = 0; $i < count($users_array); $i++){
            $id = $users_array[$i]->id;
            $pictures_array[$i]= UserPicture::where('user_id',$id)
                                            ->where('is_approved', '1')
                                            ->get("picture_url");
        }

        $result = array();
        for($j = 0; $j < count($users_array); $j++){
            $result[$j]["user"] = $users_array[$j];
            $result[$j]["pictures"] = $pictures_array[$j];
        }

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function favorite(Request $request){
        $user1_id = auth()->user()->id;
        $user1_name = auth()->user()->name;
        $user2_id = $request->user2_id;
        $user2_name = User::find($user2_id)->userName();
        
        //add to favorites
        $user_favorite = new UserFavorite();
        $user_favorite->user1_id = $user1_id;
        $user_favorite->user2_id = $user2_id;
        $user_favorite->save();


        //check if user 2 favorites user 1
        $check_favorite = UserFavorite::where('user1_id',"=", $user2_id)
                                        ->where('user2_id',"=", $user1_id)
                                        ->get();
                                        
        
        //if there's no match

        if($check_favorite->isEmpty()){

            //send a notification to user 2
            $user_notification = new UserNotification();
            $user_notification->user_id = $user2_id;
            $user_notification->created_by_user_id = $user1_id;
            $user_notification->body = $user1_name." likes you!";
            $user_notification->is_read = "0";
            $user_notification->save();

            }else{

                //send a notification to user 1 to let them know about the match
                $user_notification1 = new UserNotification();
                $user_notification1->user_id = $user1_id;
                $user_notification1->created_by_user_id = $user2_id;
                $user_notification1->body = $user2_name." matches you!";
                $user_notification1->is_read = "0";
                $user_notification1->save();

                //add user 2 to connections of user 1
                $user_connection1 = new UserConnection();
                $user_connection1->user1_id = $user1_id;
                $user_connection1->user2_id = $user2_id;
                $user_connection1->save();

                //send a notification to user 2 to let them know about the match
                $user_notification2 = new UserNotification();
                $user_notification2->user_id = $user2_id;
                $user_notification2->created_by_user_id = $user1_id;
                $user_notification2->body = $user1_name." matches you!";
                $user_notification2->is_read = "0";
                $user_notification2->save();

                //add user 1 to connections of user 2
                $user_connection2 = new UserConnection();
                $user_connection2->user1_id = $user2_id;
                $user_connection2->user2_id = $user1_id;
                $user_connection2->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'User added to favorites',
        ], 201);
    }

    //get notifications for logged in user
    public function getNotifications(){
        $user_id = auth()->user()->id;

        $notifications_array = UserNotification::where('user_id' , $user_id)
                                                ->get();

        return json_encode($notifications_array, JSON_PRETTY_PRINT);
    }

    public function addHobby(Request $request){
        $user_id = auth()->user()->id;

        $hobby = new UserHobby();
        $hobby->user_id = $user_id;
        $hobby->name = $request->name;
        $hobby->save();


        return response()->json([
            'status' => true,
            'message' => 'Hobby added successfully',
        ], 201);

    }

    public function addInterest(Request $request){
        $user_id = auth()->user()->id;

        $interest = new UserInterest();
        $interest->user_id = $user_id;
        $interest->name = $request->name;
        $interest->save();


        return response()->json([
            'status' => true,
            'message' => 'Interest added successfully',
        ], 201);

    }


    public function getFavorites(){
        $user_id = auth()->user()->id;

        $favorite_users_id = UserFavorite::where('user1_id', $user_id)
        ->pluck('user2_id')
        ->all();
        
        $favorites = array();
        for($i = 0; $i < count($favorite_users_id); $i++){
            $favorites[$i] = User::where('id', $favorite_users_id[$i])->get();
            $favorites[$i]["pictures"] = UserPicture::where('user_id', $favorite_users_id[$i])->where('is_approved', '1')->get();

        } 

        return json_encode($favorites, JSON_PRETTY_PRINT);
    }

    public function getConnections(){
        $user_id = auth()->user()->id;

        $connection_users_id = UserConnection::where('user1_id', $user_id)
        ->pluck('user2_id')
        ->all();

        $connections  = array();
        for($i = 0; $i < count($connection_users_id); $i++){
            $connections[$i]["info"] = User::where('id', $connection_users_id[$i])->get();
            $connections[$i]["pictures"] = UserPicture::where('user_id', $connection_users_id[$i])->where('is_approved', '1')->get();
        } 

        return json_encode($connections, JSON_PRETTY_PRINT);
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
