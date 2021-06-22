<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('app.env') === 'testing') {
            Schema::create('test-contests', function (Blueprint $table) {
                $table->id();
                $table->string('winner')->nullable();
                $table->integer('winning_score')->nullable();
                $table->timestamps();
            });
            return;
        }
        
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->string('winner')->nullable();
            $table->integer('winning_score')->nullable();
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
        if (config('app.env') === 'testing') {
            Schema::dropIfExists('test-contests');
            return;
        }

        Schema::dropIfExists('contests');
    }
}
