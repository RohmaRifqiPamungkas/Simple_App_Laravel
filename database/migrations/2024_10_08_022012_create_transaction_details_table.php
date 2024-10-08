<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('transaction_details')) {
            Schema::create('transaction_details', function (Blueprint $table) {
                $table->id();
                $table->foreignId('transaction_id')->constrained('transaction_headers');
                $table->foreignId('transaction_category_id')->constrained('ms_categories');
                $table->string('name');
                $table->double('value_idr');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}
