<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use WebSocket\Client;

class RedisSubscribe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:redis-subscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $ws = new Client("ws://172.19.39.225:8765");

        Redis::subscribe(['test-channel'], function (string $message) use ($ws) {
            echo $message;
            $messageJson = json_decode($message, true);

            if ($messageJson['message'] != null) {
                echo $messageJson['message'];
                $ws->text($messageJson['message']);
            }

        });
    }
}
