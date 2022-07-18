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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('id')->nullable();
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('product_id')->nullable();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('user_id')->nullable();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('vindi_id');
        });
    }
};
