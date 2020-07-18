<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_expenses', function (Blueprint $table) {
            $table->id();
            $table->string("type")->nullable();
            $table->string("ammount")->nullable();
            $table->string("description")->nullable();
            $table->integer("iou_lists_id")->nullable();
            $table->integer("master_casenumbers_id")->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->index(['iou_lists_id','master_casenumbers_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_expenses');
    }
}
