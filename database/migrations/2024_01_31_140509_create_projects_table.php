<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image')->nullable();
            $table->enum('used_software_before',[1,0])->nullable();
            $table->string('user_computer_skills')->nullable();
            $table->text('description');
            $table->text('goal')->nullable();
            $table->date('start_date')->nullable();
            $table->integer('admin_hourly_rate')->default(0);
            $table->integer('average_time_to_complete')->default(0);
            $table->date('end_date')->nullable();
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('set null');
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
        Schema::dropIfExists('projects');
    }
};
