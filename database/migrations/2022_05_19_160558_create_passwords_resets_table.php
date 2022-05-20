<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordsResetsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('passwords_resets', function (Blueprint $table) {
      $table->id();
      $table->timestamps();
      $table->integer('user_id');
      $table->string('recover_token');
      $table->boolean('valid');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('passwords_reset');
  }
}
