<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpictures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->unsigned();
            $table->integer('sequence')->unsigned();
            $table->string('src');
            $table->text('text');
            $table->enum('type',['cut','deform'])->comment='при создании миниатюр обрезать или деформировать для достижения нужных пропорций';
            $table->timestamps();
            
            $table->foreign('item_id')
                ->references('id')->on('contents')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('cpictures');
    }
}
