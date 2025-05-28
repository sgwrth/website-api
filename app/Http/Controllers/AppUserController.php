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
        $appUsers = DB::select($selectStatement, []);
        return $appUsers;
    }

    public function register(Request $request) {
        $hashedPassword = Hash::make($request["password"]);
        $insertStatement = ltrim(<<<'SQL'
            INSERT INTO app_user (username, email, password) values (?, ?, ?)
        SQL);
        $newUser = DB::insert($insertStatement, [
            $request["username"],
            $request["email"],
            $hashedPassword,
        ]);
        return $newUser;
    }
}
