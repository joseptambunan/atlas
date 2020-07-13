<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIouDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iou_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iou_id')->references('id')->on('iou_lists')->constrained();
            $table->string("type")->nullable();
            $table->string("ammount")->nullable();
            $table->text("description")->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer('adjuster_id')->nullable();
            $table->index(['adjuster_id','iou_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('iou_details');
    }
}
