<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYelpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // phone
        // avatar aka image_url
        // address
        // lat/long
        // hours
        Schema::create('yelp', function (Blueprint $table) {
            $table->string('id', 64)->nulllable(false);
            $table->string('biz_id', 64)->nullable(false);
            $table->integer('items_id')->nullable(false);
            $table->integer('rating')->nullable(false);
            $table->string('text', 255)->nullable(false);
            $table->string('review_url', 255)->nullable();
            $table->date('created_at');
            $table->date('updated_at');

            $table->unique('id');
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
        Schema::dropIfExists('yelp');
    }
}
