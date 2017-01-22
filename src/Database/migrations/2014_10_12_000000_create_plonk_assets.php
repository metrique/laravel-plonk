<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlonkAssets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plonk_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->text('params')->nullable();
            $table->string('hash', 255)->unique();
            $table->string('title', 255)->nullable();
            $table->string('alt', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->double('ratio')->default(0.00);
            $table->string('orientation', 255)->default(0.00);
            $table->string('extension', 255)->nullable();
            $table->string('mime', 255)->nullable();
            $table->integer('published')->default(1);
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
        Schema::drop('plonk_assets');
    }
}
