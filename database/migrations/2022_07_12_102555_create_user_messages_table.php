<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->text('message')->nullable();
            $table->integer('sender_id')->unsigned();
            $table->integer('received_id')->unsigned();
            $table->timestamp('received_date')->default(now());
            $table->timestamps();


        });
//        Schema::table('user_messages', function (Blueprint $table) {
//            $table->foreign('sender_id')
//                ->references('id')->on('users')
//                ->onDelete('cascade');
//            $table->foreign('received_id')
//                ->references('id')->on('users')
//                ->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
