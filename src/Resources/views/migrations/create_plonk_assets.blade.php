<?php echo '<?php' ?>

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
        Schema::create('plonk_assets', function(Blueprint $table) {
            $table->increments('id');
            $table->json('params');
            $table->string('hash', 255)->unique();
            $table->string('title', 255);
            $table->string('alt', 255);
            $table->text('description');
            $table->integer('width');
            $table->integer('height');
            $table->double('ratio')->default(0.00);
            $table->string('orientation', 255);
            $table->string('extension', 255);
            $table->string('mime', 255);
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
