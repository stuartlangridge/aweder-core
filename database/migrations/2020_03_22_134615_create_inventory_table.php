<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('inventories')) {
            Schema::create('inventories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('merchant_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->foreignId('category_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->text('title');
                $table->text('description')->nullable();
                $table->integer('price')->default(0);
                $table->tinyInteger('available')->default(1);
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
        Schema::dropIfExists('inventories');
    }
}
