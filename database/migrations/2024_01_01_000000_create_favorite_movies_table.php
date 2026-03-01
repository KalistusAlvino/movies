<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFavoriteMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('favorite_movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->string('imdb_id');
            $table->string('title');
            $table->year('year')->nullable();
            $table->string('poster')->nullable();
            $table->text('plot')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('favorite_movies');
    }
}
