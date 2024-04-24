<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address')->comment='примеры: white; 250; 43';
            $table->string('name')->comment='белый; 250, 43';
            $table->integer('parameter_id')->unsigned();
            $table->integer('sequence')->unsigned()
                ->comment='порядок следования значений внутри одного параметра';
            
            $table->foreign('parameter_id')
                ->references('id')->on('parameters')
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
        Schema::drop('values');
    }
}
