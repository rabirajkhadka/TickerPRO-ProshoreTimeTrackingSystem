<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invite_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->index();
            $table->string('token')->unique()->nullable();
            $table->integer('role_id')->unsigned();
            $table->boolean('inviteAccepted')->default(false);
            $table->boolean('resentEmail')->default(false);
            $table->boolean('inviteUserId');
            $table->dateTime('tokenExpires');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invite_tokens');
    }
};
