<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('youtube_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->references('id')->on('channels')->onUpdate('cascade')->onDelete('cascade'); //create foreign key for Channel
            $table->string('title');
            $table->integer('likes');
            $table->integer('dislikes');
            $table->text('description');
            $table->integer('duration');
            $table->integer('views');
            $table->string('thumbnail');
            $table->string('uuid'); //to use as links to youtube videos
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
        Schema::dropIfExists('youtube_videos');
    }
};
