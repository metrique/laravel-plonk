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
            $table->string('hash', 191)->unique();
            $table->string('title', 191)->nullable();
            $table->string('alt', 191)->nullable();
            $table->text('description')->nullable();
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->double('ratio')->default(0.00);
            $table->string('orientation', 191)->default(0.00);
            $table->string('extension', 191)->nullable();
            $table->string('mime', 191)->nullable();
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
