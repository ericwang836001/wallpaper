<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['code' => 401, 'message' => '账号或密码错误'], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json(['code' => 403, 'message' => '无管理后台登录权限'], 403);
        }

        // 颁发 Token (基于 Laravel Sanctum)
        $token = $user->createToken('admin_token')->plainTextToken;

        return response()->json([
            'code' => 200,
            'message' => '登录成功',
            'data' => [
                'token' => $token,
                'user' => $user->only(['id', 'username', 'name', 'avatar', 'role'])
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['code' => 200, 'message' => '退出成功']);
    }

    public function me(Request $request)
    {
        return response()->json([
            'code' => 200,
            'data' => $request->user()->only(['id', 'username', 'name', 'avatar', 'role'])
        ]);
    }
}
