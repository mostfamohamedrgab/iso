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
        Schema::create('project_rates', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('project_id')->constrained('projects');

            $table->unsignedTinyInteger('ease_of_use')->nullable();
            $table->unsignedTinyInteger('usefulness')->nullable();
            $table->unsignedTinyInteger('appearance')->nullable();
            $table->unsignedTinyInteger('clarity_and_understandability')->nullable();

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
        Schema::dropIfExists('project_rates');
    }
};
