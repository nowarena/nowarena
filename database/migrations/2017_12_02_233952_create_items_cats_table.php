<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_cats', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cats_id');
//            $table->foreign('cats_id')
//                ->references('id')->on('cats');
            $table->integer('items_id');
//            $table->foreign('items_id')
//                ->references('id')->on('items');
            //$table->integer('parent_id');
            $table->timestamps();
            $table->unique(array('cats_id', 'items_id'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_cats');
    }
}
