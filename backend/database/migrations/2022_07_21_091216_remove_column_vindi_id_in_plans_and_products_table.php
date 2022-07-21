<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('vindi_id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('vindi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('product_id')->nullable();
        });
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('product_id')->nullable();
        });
    }
};
