<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AppUserController extends Controller
{
    public function findAll() {
        $selectStatement = ltrim(<<<'SQL'
            SELECT * FROM app_user;
        SQL);
        return DB::select($selectStatement, []);
    }

    public function usersFound($email) {
        $selectStatement = ltrim(<<<'SQL'
            SELECT COUNT(*) FROM app_user WHERE email = ?
        SQL);
        return DB::scalar($selectStatement, [$email]);
    }

    public function register(Request $request) {
        $usersFound = $this->usersFound($request["email"]);
        if ($usersFound > 0) {
            return response()->json(['message' => 'error: email already exists']);
        }
        $hashedPassword = Hash::make($request["password"]);
        $insertStatement = ltrim(<<<'SQL'
            INSERT INTO app_user (username, email, password) values (:username, :email, :password)
        SQL);
        $success = DB::insert($insertStatement, [
            'username' => $request["username"],
            'email' => $request["email"],
            'password' => $hashedPassword,
        ]);
        if ($success) {
            return response()->json(['message' => 'user created!'], 201);
        } else {
            return response()->json(['message' => 'something went wrong ...'], 500);
        }
    }
}
