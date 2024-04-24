<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->unique();
            $table->string('name');
            $table->text('title');
            $table->text('description');
            $table->text('keywords');
            $table->text('text');
            $table->decimal('price',10,2)->unsigned();
            $table->decimal('discount',4,2)->unsigned()->comment='скидка в процентах, два знака после запятой';
            $table->string('vendorcode')->comment="артикул";
            $table->boolean('availability')->default(1)->comment="актуальность: 0 архивный, 1 в продаже";
            $table->timestamps();
            
            $table->index('address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('products');
    }
}

