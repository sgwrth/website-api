<?php

namespace App\Http\Controllers;

use App\Models\PostDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function getAllPosts() {
        $selectStatement = <<<'SQL'
            SELECT
                post.id
                , post.title
                , post.text
                , post.created_at AS created
                , post.updated_at AS updated
                , app_user.username AS author
                , app_user.email AS authorEmail
            FROM
                post
            JOIN
                app_user ON post.app_user = app_user.id
            ORDER BY
                created DESC
        SQL;
        $postsByUser = DB::select($selectStatement, []);
        return $postsByUser;
    }

    public function createPost(Request $request) {
        $insertStatement = <<<'SQL'
            INSERT INTO
                post (app_user, title, text)
                    values (:appUser, :title, :text)
        SQL;
        $wasPostInserted = DB::insert($insertStatement, [
            'appUser' => auth()->user()->id,
            'title' => $request['title'],
            'text' => $request['text'],
        ]);
        return $wasPostInserted
            ? response()->json(['message' => 'Post was created'], 201)
            : response()->json(['message' => 'Some error occured'], 500);
    }

    public function updatePost(Request $request, $id) {
        $updateStatement = <<<'SQL'
            UPDATE
                post
            SET
                title = :title
                , text = :text
            WHERE
                id = :postId
                AND app_user = :appUser
        SQL;
        $numberOfUpdatedRows = DB::update($updateStatement, [
            'title' => $request->title,
            'text' => $request->text,
            'postId' => $id,
            'appUser' => auth()->user()->id,
        ]);
        return $numberOfUpdatedRows;
    }

    public function getPostById($id) {
        $selectStatement = <<<'SQL'
            SELECT
                post.id
                , post.title
                , post.text
                , post.created_at AS created
                , post.updated_at AS updated
                , app_user.username AS author
                , app_user.email AS authorEmail
            FROM
                post
            JOIN
                app_user ON post.app_user = app_user.id
            WHERE
                post.id = :postId
        SQL;
        $rows = DB::select($selectStatement, ['postId' => $id]);
        return empty($rows[0])
            ? null
            : response()->json($rows[0]);
    }

    public function deletePostById($id) {
        $deleteStatement = <<<'SQL'
            DELETE FROM post WHERE post.id = :postId
        SQL;
        $numberOfRowsDeleted = DB::delete($deleteStatement, ['postId' => $id]);
        return $numberOfRowsDeleted;
    }
}
