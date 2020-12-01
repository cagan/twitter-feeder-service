<?php

namespace App\Console\Commands;

use App\Http\Clients\TwitterClient;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class ConvertMockData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mock:convert';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert mock data for faking twitter data';

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
        $clientService = new TwitterClient(new Client());
        $tweets = $clientService->get('/tweets');
        $userId = 1;

        for ($i = 0; $i < count($tweets); $i += 20) {
            for ($j = $i; $j < $i + 20; $j++) {
                $tweets[$j]['user_id'] = $userId;
            }
            $userId++;
        }

        file_put_contents(__DIR__ . '/tweets.json', json_encode($tweets));

        return true;
    }
}
