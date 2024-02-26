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
        Schema::create('project_users', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('user_id');

            $table->enum('used_software_before',[1,0])->nullable();
            $table->decimal('hourly_rate', 8, 2)->nullable(); // Assuming hourly rate is a decimal value
            
            $table->string('gender')->nullable();
            $table->integer('year_of_experience')->nullable();
            $table->string('user_computer_skills')->nullable();
            
            $table->enum('user_answer',[1,0])->default(0);
            
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('project_users');
    }
};
