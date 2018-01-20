<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->string('id', 64)->nulllable(false);
            $table->string('user_id', 64)->nullable(false);
            $table->string('screen_name', 64)->nullable(false);
            $table->string('text', 255)->nullable(false);
            $table->string('urls', 255)->nullable();
            $table->mediumText('media')->nullable();
            $table->string('in_reply_to_status_id', 64)->nullable();
            $table->string('in_reply_to_user_id', 64)->nullable();
            $table->timestamps();
            $table->unique('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tweets');
    }
}
