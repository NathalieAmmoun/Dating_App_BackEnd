<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserPicture extends Model{
	protected $table = "user_pictures";

    public function User(){
        return $this->belongsTo(User::class);
    }

}


?>
