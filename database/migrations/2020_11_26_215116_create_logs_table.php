<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('unit_id')->unsigned();
            $table->date('date');
            $table->string('student_id', 6);
            $table->text('student_name');
            $table->text('group_name')->nullable();
            $table->text('type');
            $table->integer('hour_start')->default(0);
            $table->integer('hour_end')->default(0);
            $table->decimal('duration', 8, 1)->default(0);
            $table->string('time_start', 5);
            $table->string('time_end', 5)->nullable();
            $table->text('duration_text')->nullable();
            $table->text('reason')->nullable();
            $table->text('comment')->nullable();
            $table->text('logged_by')->nullable();
            $table->timestamps();
            $table->boolean('handled1')->default(false);
            $table->boolean('handled2')->default(false);
            $table->boolean('handled3')->default(false);
            $table->boolean('handled4')->default(false);
            $table->boolean('handled5')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
