<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CatsPAndC extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cats_p_and_c', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('parent_id');
            $table->integer('child_id');
            $table->timestamps();
            $table->unique(array('parent_id', 'child_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cats_p_and_c');
    }
}
