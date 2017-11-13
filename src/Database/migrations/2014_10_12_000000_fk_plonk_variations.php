<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FkPlonkVariations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plonk_variations', function (Blueprint $table) {
            $table->integer('plonk_assets_id')
                ->nullable()
                ->unsigned();

            $table->foreign('plonk_assets_id')
                ->references('id')
                ->on('plonk_assets')
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
        //
    }
}
