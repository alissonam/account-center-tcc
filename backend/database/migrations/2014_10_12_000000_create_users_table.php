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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('permission_id');
            $table->string('name');
            $table->string('last_name')->nullable();
            $table->string('document', 20)->nullable()->unique();
            $table->string('registration', 30)->nullable()->unique();
            $table->string('email')->unique();
            $table->string('phone', 20)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->enum('role', ['admin', 'member'])->default('member');
            $table->enum('status', ['active', 'blocked', 'pending_password'])->default('pending_password');
            $table->string('zipcode', 10)->nullable();
            $table->char('state', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('street')->nullable();
            $table->string('number', 10)->nullable();
            $table->string('complement')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('permission_id', 'fk_u_permission_id')
                ->references('id')
                ->on('permissions')
                ->onUpdate('RESTRICT')
                ->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_u_permission_id');
        });

        Schema::dropIfExists('users');
    }
};
