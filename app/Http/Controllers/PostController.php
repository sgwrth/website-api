<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getAllPosts() {
        $selectStatement = ltrim(<<<'SQL'
            SELECT * FROM post
        SQL);
        return DB::select($selectStatement, []);
    }

    public function createPost(Request $request) {
        $user = auth()->user();
        $insertStatement = ltrim(<<<'SQL'
            INSERT INTO post (app_user, text, title) values (:appUser, :title, :text)
        SQL);
        $wasPostInserted = DB::insert($insertStatement, [
            'appUser' => $user->id,
            'title' => $request['title'],
            'text' => $request['text'],
        ]);
        if ($wasPostInserted) {
            return response()->json(['message' => 'post was created'], 201);
        } else {
            return response()->json(['message' => 'some error occured, no post created'], 500);
        }
    }
}
