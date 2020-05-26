<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNormalOpeningHoursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('normal_opening_hours')) {
            Schema::create('normal_opening_hours', function (Blueprint $table) {
                $table->id();
                $table->foreignId('merchant_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
                $table->integer('day_of_week');
                $table->time('open_time');
                $table->time('close_time');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('normal_opening_hours');
    }
}
