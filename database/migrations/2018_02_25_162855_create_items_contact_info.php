<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsContactInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('items_id')->unique();
            $table->string('biz_id')->unique();
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('business')->nullable(true);
            $table->string('address')->nullable(true);
            $table->string('address2')->nullable(true);
            $table->string('postal_code')->nullable(true);
            $table->string('city')->nullable(true);
            $table->string('state')->nullable(true);
            $table->string('phone_number')->nullable(true);
            $table->string('email', 100)->nullable(true);
            $table->string('website')->nullable(true);
            $table->string('lon')->nullable(true);
            $table->string('lat')->nullable(true);
            $table->text('hours')->nullable(true);
            $table->unique(array('biz_id', 'items_id'));
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
        Schema::dropIfExists('contact_info');
    }
}
