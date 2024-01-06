<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // stripe suscriptions
        Schema::create('suscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('tenant_user_id');
            $table->string('stripe_id');
            $table->string('stripe_plan');
            $table->integer('quantity');
            $table->string('stripe_status');
            // the expiration date is the date when the suscription ends and the suscription is renewed, the ends_at is the date when the suscription ends and is not renewed
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('expiration_date')->nullable();
            $table->foreign('tenant_user_id')->references('id')->on('tenant_users');
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
        Schema::dropIfExists('suscriptions');
    }
}
