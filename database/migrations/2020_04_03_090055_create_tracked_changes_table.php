<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrackedChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasTable('tracked_changes')) {
            Schema::create('tracked_changes', function (Blueprint $table) {
                $table->id();
                $table->char('subject_id', 36)->index()->comment('id of the model');
                $table->string('subject_type')->index()->comment('The model used');
                $table->string('user_id')->index()->nullable();
                $table->string('name')->comment('name of the model and event');
                $table->json('data');
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
        Schema::dropIfExists('tracked_changes');
    }
}
