<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediaUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id', 64);
            $table->string('username', 64);
            $table->string('site', 32);
            $table->integer('active')->default(0);
            $table->unique(array('user_id', 'site'));
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
        Schema::dropIfExists('social_media_users');
    }
}
