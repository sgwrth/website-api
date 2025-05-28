<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppUserController extends Controller
{
    public function findAll() {

        $selectStatement = ltrim(<<<'SQL'
            SELECT * FROM app_user;
        SQL);

        $appUsers = DB::select($selectStatement, []);

        return $appUsers;
    }
}
