<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('web_request_logs', function (Blueprint $table) {
            $table->increments('id')->uniqid();
            $table->timestamp('request_time')->useCurrent();
            $table->string('request_method', 10);
            $table->text('request_url', 1000);
            $table->integer('responce_http_code');
            $table->longText('responce_body')->nullable();
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
        Schema::dropIfExists('web_request_logs');
    }
}
