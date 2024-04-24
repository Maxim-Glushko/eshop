<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->unique()->comment='примеры: color; weight-g; size';
            $table->string('name')->comment='цвет; вес, г; размер';
            $table->integer('sequence')->unsigned()->comment='порядок следования параметров на странице';
            $table->boolean('for_order')->comment='обязательно ли выбирать параметр при заказе';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('parameters');
    }
}
