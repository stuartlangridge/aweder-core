<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('order_reminders')) {
            Schema::create('order_reminders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')
                    ->constrained()
                    ->onUpdate('CASCADE')
                    ->onDelete('CASCADE');
                $table->integer('reminder_time')
                    ->nullable()
                    ->comment('This is the minute reminder time which should allow us to stop duplication being sent');
                $table->dateTime('sent')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_reminders');
    }
}
