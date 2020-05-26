<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTableField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->foreignId('merchant_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->string('status')->nullable();
                $table->tinyInteger('is_delivery')->default(0);
                $table->string('customer_name')->nullable();
                $table->string('customer_email')->nullable();
                $table->text('customer_address')->nullable();
                $table->string('customer_phone')->nullable();
                $table->timestamp('available_time')->nullable();
                $table->text('rejection_reason')->nullable();
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
        Schema::dropIfExists('orders');
    }
}
