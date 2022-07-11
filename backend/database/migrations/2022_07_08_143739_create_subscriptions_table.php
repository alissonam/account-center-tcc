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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('plan_id', 'fk_s_plan_id')
                ->references('id')
                ->on('plans')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('product_id', 'fk_s_product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_s_user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('RESTRICT')
                ->onDelete('CASCADE');
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
            $table->dropForeign('fk_s_plan_id');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign('fk_s_product_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_s_user_id');
        });
        Schema::dropIfExists('subscriptions');
    }
};
