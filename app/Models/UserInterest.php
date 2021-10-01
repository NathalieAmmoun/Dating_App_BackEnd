<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserInterest extends Model{
	protected $table = "user_interests";

    public function User(){
        return $this->belongsTo(User::class);
    }
}


?>
