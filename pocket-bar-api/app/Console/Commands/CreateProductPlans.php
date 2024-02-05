<?php

namespace App\Console\Commands;

use App\Models\Plan;
use Illuminate\Console\Command;
use Stripe\Stripe;
use \Stripe\Product;

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
    protected $description = 'Create the product plans for the tenant subscriptions in stripe';

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
        // **Los ultimos dos digitos de la propierdad amount son los centavos
        Stripe::setApiKey(env('STRIPE_SECRET'));
        // $product = \Stripe\Product::retrieve([
        //     'id' => 'prod_PSgrmpZCPmOTIi',

        // ]);
        // if (empty($product)) {

        // }
        // echo $product->name;
        $product = \Stripe\Product::create([
            'name' => 'Beer Tier',
        ]);
        // crear el plan mensual y anual de 10 y 100 dolares respectivamente
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Beer Monthly',
            'interval' => 'month',
            'currency' => 'usd',
            'amount' => 1000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = $stripePlan->nickname;
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Beer Annual',
            'interval' => 'year',
            'currency' => 'usd',
            'amount' => 10000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = $stripePlan->nickname;
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
        $product = \Stripe\Product::create([
            'name' => 'Mojito Tier',
        ]);
        // crear el plan mensual y anual de 10 y 100 dolares respectivamente
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Mojito Monthly',
            'interval' => 'month',
            'currency' => 'usd',
            'amount' => 2000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = $stripePlan->nickname;
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
        $stripePlan = \Stripe\Plan::create([
            'product' => $product->id,
            'nickname' => 'Mojito Annual',
            'interval' => 'year',
            'currency' => 'usd',
            'amount' => 20000,
        ]);
        // insertar los planes en la base de datos
        $plan = new \App\Models\Plan();
        $plan->name = $stripePlan->nickname;
        $plan->stripe_id = $stripePlan->id;
        $plan->interval = $stripePlan->interval;
        $plan->currency = $stripePlan->currency;
        $plan->amount = $stripePlan->amount;
        $plan->save();
    }
}
