<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('username')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('cover_photo_url')->default('users/cover_photos/default_cover_photo.png');
            $table->string('photo_url')->default('users/photos/mysteryman.png');
            $table->string('bio')->nullable();
            $table->string('money_separators')->default(',.');
            $table->rememberToken();
            $table->tinyInteger('private')->default(0);
            $table->tinyInteger('verified')->default(0);
            $table->tinyInteger('admin')->default(0);
            $table->timestamps();
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
    }
}
