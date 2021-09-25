<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->integer('region_id');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('admins')->onDelete('cascade');
            $table->integer('propertytypes_id');
            $table->foreign('propertytypes_id')->references('id')->on('propertytypes')->onDelete('cascade');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('details_en')->nullable();
            $table->string('details_ar')->nullable();
            $table->string('fulladdress_en')->nullable();
            $table->string('fulladdress_ar')->nullable();
            $table->double('price');
            $table->string('image')->nullable();
            $table->integer('status_id');
            $table->double('lat');
            $table->double('long');
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
        Schema::dropIfExists('properties');
    }
}
