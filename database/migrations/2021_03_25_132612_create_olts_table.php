<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOltsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('olts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome');
            $table->string('ip');
            $table->string('user');
            $table->string('pass');
            $table->integer('slot');
            $table->integer('pon');
            $table->string('vendor');
            $table->string('model')->nullable();
            $table->string('firmware')->nullable();
            $table->integer('last_cpu')->nullable();
            $table->integer('last_mem')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('olts');
    }
}
