<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIousTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('iou_lists', function (Blueprint $table) {
            $table->id();
            $table->string("title")->nullable();
            $table->string("client")->nullable();
            $table->string("division")->nullable();
            $table->string("type_of_survey")->nullable();
            $table->string("location")->nullable();
            $table->dateTime("starttime")->nullable();
            $table->dateTime("endtime")->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer('adjuster_id')->nullable();
            $table->string("document_number")->nullable();
            $table->dateTime("document_upload_at")->nullable();
            $table->integer("document_upload_by")->nullable();
            $table->dateTime("finish_at")->nullable();
            $table->index(['adjuster_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ious');
    }
}
