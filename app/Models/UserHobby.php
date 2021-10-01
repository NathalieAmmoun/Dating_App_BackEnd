<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserHobby extends Model{
	protected $table = "user_hobbies";

    public function User(){
        return $this->belongsTo(User::class);
    }

}


?>
