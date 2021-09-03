<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news_blocks', function (Blueprint $table) {
            $table->increments('id')->unique();
            $table->string('name',1000);
            $table->string('shortlink',300)->unique();
            $table->string('description',300);
            $table->timestamp('publish_date');
            $table->string('author',255)->nullable();
            $table->json('images')->nullable();
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
        Schema::dropIfExists('news_blocks');
    }
}
