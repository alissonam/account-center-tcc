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
        Schema::create('sugestions', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });

        Schema::table('sugestions', function (Blueprint $table) {
            $table->foreign('product_id', 'fk_sg_product_id')
                ->references('id')
                ->on('products')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');

            $table->foreign('user_id', 'fk_sg_user_id')
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
        Schema::table('sugestions', function (Blueprint $table) {
            $table->dropForeign('fk_sg_product_id');
            $table->dropForeign('fk_sg_user_id');
        });
        Schema::dropIfExists('sugestions');
    }
};
