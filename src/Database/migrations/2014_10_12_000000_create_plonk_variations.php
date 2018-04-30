<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlonkVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plonk_variations', function (Blueprint $table) {
            $table->increments('id');
            $table->text('params')->nullable();
            $table->string('name', 191);
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->integer('quality')->default(0);
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
        Schema::drop('plonk_variations');
    }
}
