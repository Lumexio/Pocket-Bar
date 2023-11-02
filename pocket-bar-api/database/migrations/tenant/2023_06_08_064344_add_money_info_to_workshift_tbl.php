<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoneyInfoToWorkshiftTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('workshifts', function (Blueprint $table) {
            $table->integer('start_money')->default(0)->after('active');
            $table->integer('end_money')->default(0)->after('start_money');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('workshifts', function (Blueprint $table) {
            $table->dropColumn('start_money');
            $table->dropColumn('end_money');
        });
    }
}
