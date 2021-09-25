<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertydetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propertydetails', function (Blueprint $table) {
            $table->id();
            $table->integer('property_id');
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->integer('beds')->default(0);
            $table->integer('baths')->default(0);
            $table->integer('kitchens')->default(0);
            $table->integer('area');
            $table->integer('purpose');
            $table->integer('completion_status');
            $table->integer('ownership_status');
            // Should be in propertyfeatures table as foreign key from features table
            $table->integer('furnishing');
            // yearly, monthly, weekly, dialy
            $table->integer('rent_frequency');
            $table->string('referencenumber')->nullable();
            $table->string('permitnumber')->nullable();
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
        Schema::dropIfExists('propertydetails');
    }
}
