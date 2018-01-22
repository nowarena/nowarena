<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialMediaAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('items_id')->default(0);
            $table->string('source_id', 64);
            $table->string('username', 64);
            $table->string('avatar', 255);
            $table->string('site', 32);
            $table->integer('is_active')->default(0);
            $table->integer('use_avatar')->default(0);
            $table->integer('is_primary')->default(0);
            $table->unique(array('source_id', 'site'));
            $table->timestamps();
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media_accounts');
    }
}
