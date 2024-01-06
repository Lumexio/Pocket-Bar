<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Stripe;

class CreateProductPlans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:create-product-plans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create the product plans for the tenant suscriptions in stripe';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $product = \Stripe\Product::create([
            'name' => 'Pocket Bar Suscription',
        ]);
        // crear el plan mensual y anual de 10 y 100 dolares respectivamente
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Pocket Bar Monthly',
            'interval' => 'month',
            'currency' => 'usd',
            'amount' => 1000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = 'Pocket Bar Monthly';
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Pocket Bar Annual',
            'interval' => 'year',
            'currency' => 'usd',
            'amount' => 10000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = 'Pocket Bar Annual';
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
    }
}
