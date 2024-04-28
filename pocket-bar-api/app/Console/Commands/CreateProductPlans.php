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
        $product = \Stripe\Product::all();
        if (empty($product->data)) {
            $product = \Stripe\Product::create([
                'name' => 'Beer Tier',
            ]);
            $benefits = [
                'Gestion de inventarios',
                'Toma de ordenes',
                'Cobro de cover',
                'Comandas digitales',
            ];
            // crear el plan mensual y anual de 10 y 100 dolares respectivamente
            $stripePlan = \Stripe\Plan::create([
                'product' => $product->id,
                'nickname' => 'Beer Monthly',
                'interval' => 'month',
                'currency' => 'usd',
                'metadata' => $benefits,
                'amount' => 1000,
            ]);
            // insertar los planes en la base de datos
            $plan = new \App\Models\Plan();
            $plan->name = $stripePlan->nickname;
            $plan->stripe_id = $stripePlan->id;
            $plan->interval = $stripePlan->interval;
            $plan->currency = $stripePlan->currency;
            $plan->amount = $stripePlan->amount;
            $plan->benefits =  \implode(',', $benefits);
            $plan->save();
            $stripePlan = \Stripe\Plan::create([
                'product' => $product->id,
                'nickname' => 'Beer Annual',
                'interval' => 'year',
                'currency' => 'usd',
                'metadata' => $benefits,
                'amount' => 10000,
            ]);
            // insertar los planes en la base de datos
            $plan = new \App\Models\Plan();
            $plan->name = $stripePlan->nickname;
            $plan->stripe_id = $stripePlan->id;
            $plan->interval = $stripePlan->interval;
            $plan->currency = $stripePlan->currency;
            $plan->amount = $stripePlan->amount;
            $plan->benefits = \implode(',', $benefits);
            $plan->save();
            $product = \Stripe\Product::create([
                'name' => 'Mojito Tier',
            ]);
            $benefits = [
                'Gestion de inventarios', 'Toma de ordenes', 'Cobro de cover'
            ];
            // crear el plan mensual y anual de 10 y 100 dolares respectivamente
            $stripePlan = \Stripe\Plan::create([
                'product' => $product->id,
                'nickname' => 'Mojito Monthly',
                'interval' => 'month',
                'currency' => 'usd',
                'metadata' => $benefits,
                'amount' => 2000,
            ]);
            // insertar los planes en la base de datos
            $plan = new \App\Models\Plan();
            $plan->name = $stripePlan->nickname;
            $plan->stripe_id = $stripePlan->id;
            $plan->interval = $stripePlan->interval;
            $plan->currency = $stripePlan->currency;
            $plan->amount = $stripePlan->amount;
            $plan->benefits =  \implode(',', $benefits);
            $plan->save();
            $stripePlan = \Stripe\Plan::create([
                'product' => $product->id,
                'nickname' => 'Mojito Annual',
                'interval' => 'year',
                'currency' => 'usd',
                'metadata' => $benefits,
                'amount' => 20000,
            ]);
            // insertar los planes en la base de datos
            $plan = new \App\Models\Plan();
            $plan->name = $stripePlan->nickname;
            $plan->stripe_id = $stripePlan->id;
            $plan->interval = $stripePlan->interval;
            $plan->currency = $stripePlan->currency;
            $plan->amount = $stripePlan->amount;
            $plan->benefits =  \implode(',', $benefits);
            $plan->save();
        } else {
            echo 'Products already created on stripe';
        }
    }
}
