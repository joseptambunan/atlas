<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_insurances', function (Blueprint $table) {
            $table->id();
            $table->string('insurance_name')->nullable();
            $table->timestamps();
            $table->integer("created_by")->nullable();
            $table->integer("updated_by")->nullable();
            $table->datetime("deleted_at")->nullable();
            $table->integer("deleted_by")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('master_insurances');
    }
}
