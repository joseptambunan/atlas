<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterCasenumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_casenumbers', function (Blueprint $table) {
            $table->id();
            $table->string("case_number")->nullable();
            $table->text("title")->nullable();
            $table->string("invoice_number")->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer("invoice_number_by")->nullable();
            $table->datetime("invoice_number_at")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_casenumbers');
    }
}
