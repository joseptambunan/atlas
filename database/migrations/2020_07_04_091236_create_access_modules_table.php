<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccessModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('access_modules', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
            $table->integer('created')->nullable();
            $table->integer('read')->nullable();
            $table->integer('update')->nullable();
            $table->integer('insert')->nullable();
            $table->foreignId('modules_id')->references('id')->on('master_modules')->constrained();
            $table->index(['modules_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_modules');
    }
}
