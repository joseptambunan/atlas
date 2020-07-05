<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentIdToMasterApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('master_approvals', function (Blueprint $table) {
            $table->foreignId('document_id')->references('id')->on('master_documents')->constrained();
            $table->index(['document_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('master_approvals', function (Blueprint $table) {
            //
        });
    }
}
