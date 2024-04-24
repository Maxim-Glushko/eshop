<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('name')->comment='на случай, если продукт удалят, чтобы в истории осталось';
            $table->decimal('price',10,2)->unsigned();
            $table->integer('amount')->unsigned();
            $table->decimal('discount',4,2)->unsigned();
            $table->string('parameters')->comment='кириллическая строка - набор параметров, например: размер=XXL, цвет=красный; только по одному значению обязательных параметров';
            
            $table->foreign('product_id')
                ->references('id')->on('products');
            
            $table->foreign('order_id')
                ->references('id')->on('orders')
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
        Schema::drop('order_product');
    }
}
