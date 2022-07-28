<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSnmpToOlts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('olts', function (Blueprint $table) {
            $table->boolean('snmp');
            $table->string('community');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('olts', function (Blueprint $table) {
            $table->dropColumn('snmp');
            $table->dropColumn('community');
        });
    }
}
