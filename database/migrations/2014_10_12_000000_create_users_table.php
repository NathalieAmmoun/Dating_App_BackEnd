<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
			$table->boolean('is_admin');
            $table->string('name');
            $table->string('email')->unique();
			$table->string('password');
			$table->boolean('gender'); /* 0 for Male, 1 for Female */
			$table->boolean('interested_in'); /* 0: interested in Men, 1: interested in Women */
			$table->date('dob'); /* Date of birth */
			$table->string('height')->nullable();
			$table->string('nationality')->nullable();
			$table->text('bio')->nullable();
            $table->rememberToken();
            $table->timestamps();
			$table->softDeletes();
        });

		Schema::create('user_pictures', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
            $table->text('picture_url');
			$table->boolean('is_profile_picture');
			$table->boolean('is_approved');
            $table->timestamps();
			$table->softDeletes();
        });

		Schema::create('user_connections', function (Blueprint $table) {
            $table->id();
			$table->integer('user1_id');
            $table->integer('user2_id');
            $table->timestamps();
			$table->softDeletes();
        });

        Schema::create('user_favorites', function (Blueprint $table) {
            $table->id();
			$table->integer('user1_id');
            $table->integer('user2_id');
            $table->timestamps();
			$table->softDeletes();
        });


		Schema::create('user_blocked', function (Blueprint $table) {
            $table->id();
			$table->integer('from_user_id');
            $table->integer('to_user_id');
            $table->timestamps();
			$table->softDeletes();
        });

		// Schema::create('user_messages', function (Blueprint $table) {
        //     $table->id();
		// 	$table->integer('sender_id');
        //     $table->integer('receiver_id');
		// 	$table->text('body');
		// 	$table->boolean('is_approved');
		// 	$table->boolean('is_read');
        //     $table->timestamps();
		// 	$table->softDeletes();
        // });



		Schema::create('user_interests', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
            $table->string('name');
            $table->timestamps();
			$table->softDeletes();
        });

		Schema::create('user_hobbies', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
            $table->string('name');
            $table->timestamps();
			$table->softDeletes();
        });

		Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
			$table->integer('user_id');
            $table->integer('created_by_user_id');
            $table->string('body');
			$table->boolean('is_read');
            $table->timestamps();
			$table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_pictures');
        Schema::dropIfExists('user_connections');
        Schema::dropIfExists('user_interests');
        Schema::dropIfExists('user_hobbies');
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('user_favorites');
    }
}
