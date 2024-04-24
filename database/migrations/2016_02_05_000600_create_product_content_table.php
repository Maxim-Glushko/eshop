<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductContentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_content', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('content_id')->unsigned();
            $table->boolean('main')->default(0)->comment='основная ли это категория для данного продукта';
            
            $table->foreign('product_id')
                ->references('id')->on('products')
                ->onDelete('cascade');
            
            $table->foreign('content_id')
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
        Schema::drop('product_content');
    }
}
