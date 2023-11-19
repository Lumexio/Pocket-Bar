<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTenantUserReferenceToTenants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_user_id')->nullable();
            $table->foreign('tenant_user_id')->references('id')->on('tenant_users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropForeign(['tenant_user_id']);
            $table->dropColumn('tenant_user_id');
        });
    }
}
