<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function sendMessage()
    {
        Redis::publish('test-channel', json_encode([
            'name' => 'Adam Wathan'
        ]));
    }
}
