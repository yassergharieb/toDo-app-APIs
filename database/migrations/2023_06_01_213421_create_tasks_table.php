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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

           
            $table->Text('task_body');
            $table->enum('status' , ['in progress' , 'not started yet' , 'completed' ]);
            $table->enum('prioirty' , ['high' , 'mid' , 'low' ]); // set gate and policy do change just has in progress or not started yet

            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();

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
        Schema::dropIfExists('tasks');
    }
};
