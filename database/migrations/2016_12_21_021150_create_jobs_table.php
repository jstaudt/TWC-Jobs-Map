<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('openings');
            $table->string('job_title', 70);
            $table->string('city', 70);
            $table->string('employer', 100);
            $table->text('job_description');
            $table->decimal('min_pay_rate', 10, 2);
            $table->decimal('max_pay_rate', 10, 2);
            $table->string('occupational_category', 70);
            $table->string('link', 100);
            $table->string('work_week_code', 30);
            $table->string('assigned_staff', 35);
            $table->string('address', 100);
            $table->string('zip', 12);
            $table->string('state', 2);
            $table->string('entry_level', 3);
            $table->string('email', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
