<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('merchants')) {
            Schema::create('merchants', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('contact_number')->nullable();
                $table->text('address')->nullable();
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('merchants');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
