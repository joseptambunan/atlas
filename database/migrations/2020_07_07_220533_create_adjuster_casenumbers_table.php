<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdjusterCasenumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adjuster_casenumbers', function (Blueprint $table) {
            $table->id();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->foreignId('adjuster_id')->references('id')->on('master_adjusters')->constrained();
            $table->foreignId('case_number_id')->references('id')->on('master_casenumbers')->constrained();
            $table->timestamps();
            $table->index(['adjuster_id','case_number_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adjuster_casenumbers');
    }
}