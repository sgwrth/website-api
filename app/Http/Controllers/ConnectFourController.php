<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ConnectFourController extends Controller
{
    public function play(Request $request) {
        $payload = $request->all(); # $payload = associative array

        # Http automatically converts $payload to JSON.
        $response = Http::post('http://localhost:5000/', $payload);
        return response()->json($response->json(), $response->status());
    }
}
