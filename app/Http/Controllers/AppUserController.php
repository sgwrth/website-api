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

    private function usersFound($email) {
        $selectStatement = ltrim(<<<'SQL'
            SELECT COUNT(*) FROM app_user WHERE email = ?
        SQL);
        return DB::scalar($selectStatement, [$email]);
    }

    public function getMe() {
        $user = auth()->user();
        $selectStatement = ltrim(<<<'SQL'
            SELECT u.id
                , u.email
                , u.username
                , r.name AS role
                , u.created_at AS created
                , u.updated_at AS updated
            FROM app_user AS u
                , roles AS r
            WHERE u.id = :userId
                AND r.id = u.role_id
        SQL);
        $user = DB::select($selectStatement, [
            'userId' => $user->id,
        ]);
        return $user;
    }

    public function register(Request $request) {
        $usersFound = $this->usersFound($request['email']);
        if ($usersFound > 0) {
            return response()->json(['message' => 'error: email already existss']);
        }
        $hashedPassword = Hash::make($request['password']);
        $insertStatement = ltrim(<<<'SQL'
            INSERT INTO app_user (username, email, password) values (:username, :email, :password)
        SQL);
        $success = DB::insert($insertStatement, [
            'username' => $request['username'],
            'email' => $request['email'],
            'password' => $hashedPassword,
        ]);
        if ($success) {
            return response()->json(['message' => 'user created!'], 201);
        } else {
            return response()->json(['message' => 'something went wrong ...'], 500);
        }
    }

    public function login(Request $request) {
        $selectOneStatement = ltrim(<<<'SQL'
            SELECT *
            FROM app_user
            WHERE email = :email
        SQL);
        $user = DB::selectOne($selectOneStatement, ['email' => $request['email']]);
        if (!$user) {
            return response()->json(['message' => 'login failed: invalid credentials' ], 401);
        }
        if (Hash::check($request['password'], $user->password)) {
            $userModel = \App\Models\AppUser::find($user->id); // object -> Eloq. model, enabling createToken() use
            if (!$this->existsApiToken($user->id)) {
                $token = $this->createToken($userModel, $user->username)->plainTextToken;
            } else {
                DB::transaction(function () use ($user, $userModel, &$token) {
                    $this->deleteToken($user->id);
                    $token = $this->createToken($userModel, $user->username)->plainTextToken;
                });
            }
            if (!$token) {
                return response()->json(['message' => 'error: something went wrong'], 500);
            }
            return response()->json([
                'message' => 'login successful',
                'token' => $token,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $userModel->role->name,
            ]);
        }
        return response()->json(['message' => 'login failed: invalid credentials'], 401);
    }

    private function existsApiToken($userId) {
        $selectOneStatement = ltrim(<<<'SQL'
            SELECT *
            FROM personal_access_tokens
            WHERE tokenable_id = :userId
        SQL);
        $result = DB::selectOne($selectOneStatement, ['userId' => $userId]);
        return ($result) ? true : false;
    }

    private function createToken($userModel, $username) {
        return $userModel->createToken($username.'-API_Token');
    }

    private function deleteToken($userId) {
        $deleteStatement = ltrim(<<<'SQL'
            DELETE FROM personal_access_tokens
            WHERE tokenable_id = :userId
        SQL);
        return DB::delete($deleteStatement, ['userId' => $userId]);
    }
}
