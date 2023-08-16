<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Illuminate\Console\Command;

class SyncCurrencies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync currencies from API to DB';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getCurrencies($base = "MXN")
    {
        $client = new \GuzzleHttp\Client();
        $api_key = env("EXCHANGE_RATE_API_KEY");
        $response = $client->request('GET', 'http://api.exchangeratesapi.io/v1/latest?access_key=' . $api_key . '&base=' . $base);
        $response = json_decode($response->getBody()->getContents(), true);
        return $response['rates'];
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $baseCurrency = Currency::where("default", 1)->first();
        $currencies = $this->getCurrencies($baseCurrency->code);
        dd($currencies);
        $currenciesToSync = Currency::where("default", 0)->get();
        foreach ($currenciesToSync as $currency) {
            $currency->rate = $currencies[$currency->code];
            $currency->save();
        }
        return 0;
    }
}
