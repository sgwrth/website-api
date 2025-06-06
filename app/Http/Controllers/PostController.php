<?php

namespace App\Http\Controllers;

use App\Models\PostDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getAllPosts() {
        # get all posts from logged in user
        $selectStatement = ltrim(<<<'SQL'
            SELECT post.id
                , post.title
                , post.text
                , post.created_at AS created
                , post.updated_at AS updated
                , app_user.username AS author
                , app_user.email AS author_email
            FROM post
            JOIN app_user ON post.app_user = app_user.id
            WHERE app_user.id = :userId
            ORDER BY created DESC
        SQL);
        $user = auth()->user();
        $postsByUser = DB::select($selectStatement, [
            'userId' => $user->id
        ]);
        $postsByUserDto = [];
        foreach ($postsByUser as $post) {
            $postsByUserDto[] = PostDto::fromStdClass($post);
        }
        return $postsByUserDto;
        // return $postsByUser;
    }

    public function createPost(Request $request) {
        $user = auth()->user();
        $insertStatement = ltrim(<<<'SQL'
            INSERT INTO post (app_user, title, text) values (:appUser, :title, :text)
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

    public function updatePost(Request $request) {
        $user = auth()->user();

        $updateStatement = ltrim(<<<'SQL'
            UPDATE post
            SET title = :title
                , text = :text
            WHERE id = :postId
                AND app_user = :appUser
        SQL);
        $updatedRow = DB::update($updateStatement, [
            'title' => $request->title,
            'text' => $request->text,
            'postId' => $request->postId,
            'appUser' => $user->id,
        ]);
    }
}
