<?php echo '<?php' ?>

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
        Schema::create('plonk_variations', function(Blueprint $table) {
            $table->increments('id');
            $table->json('params');
            $table->string('name', 255);
            $table->integer('width');
            $table->integer('height');
            $table->integer('quality');
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
