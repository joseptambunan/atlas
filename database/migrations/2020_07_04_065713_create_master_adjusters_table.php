<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterAdjustersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_adjusters', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("nik")->nullable();
            $table->string("email")->nullable();
            $table->string("phone")->nullable();
            $table->longtext("thubmnail")->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->foreignId('position_id')->references('id')->on('master_positions')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_adjusters');
    }
}
