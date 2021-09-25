<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->integer('blogcategory_id');
            $table->foreign('blogcategory_id')->references('id')->on('blogcategories')->onDelete('cascade');
            $table->string("title_en")->nullable();
            $table->string("title_ar")->nullable();
            $table->text("description_en")->nullable();
            $table->text("description_ar")->nullable();
            $table->string("image")->nullable();
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
        Schema::dropIfExists('blogs');
    }
}
