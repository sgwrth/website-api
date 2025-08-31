<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class RedisController extends Controller
{
    public function sendMessage(Request $request)
    {

        $message = '';
        if ($request->json()->all() != null) {
            $requestBody = $request->json()->all();
            $message = $requestBody['message'];
            echo $message . "\n";
        }

        # Ignore error regarding 'publish' method, it works anyway.  (LSP error?)
        Redis::publish('test-channel', json_encode([
            'message' => $message
        ]));
    }
}
