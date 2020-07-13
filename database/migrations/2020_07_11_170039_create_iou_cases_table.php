<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIouCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iou_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('adjuster_casenumber_id')->references('id')->on('adjuster_casenumbers')->constrained();
            $table->foreignId('iou_lists_id')->references('id')->on('iou_lists')->constrained();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer('adjuster_id')->nullable();
            $table->index(['adjuster_id','adjuster_casenumber_id','iou_lists_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iou_cases');
    }
}
