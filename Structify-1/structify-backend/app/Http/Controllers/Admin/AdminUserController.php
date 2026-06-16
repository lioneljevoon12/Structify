<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($q) =>
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%")
                  ->orWhere('username', 'like', "%{$request->search}%")
            )
            ->when($request->role, fn($q) =>
                $q->where('role', $request->role)
            )
            ->select('id', 'name', 'username', 'email', 'role', 'is_banned', 'created_at')
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::select(
            'id', 'name', 'username', 'email',
            'bio', 'avatar', 'role', 'is_banned', 'created_at'
        )->findOrFail($id);

        return response()->json(['data' => $user]);
    }

    public function ban(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin' || $user->role === 'developer') {
            return response()->json([
                'message' => 'Tidak bisa ban akun admin atau developer.'
            ], 403);
        }

        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Tidak bisa ban diri sendiri.'
            ], 403);
        }

        $user->update(['is_banned' => !$user->is_banned]);

        $status = $user->is_banned ? 'dibanned' : 'di-unban';

        return response()->json([
            'message'   => "User berhasil {$status}",
            'is_banned' => $user->is_banned,
        ]);
    }

    public function promote(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|in:user,admin',
        ]);

        $user = User::findOrFail($id);

        if ($user->role === 'developer') {
            return response()->json([
                'message' => 'Role developer tidak bisa diubah.'
            ], 403);
        }

        if ($user->id === $request->user()->id) {
            return response()->json([
                'message' => 'Tidak bisa mengubah role diri sendiri.'
            ], 403);
        }

        $user->update(['role' => $request->role]);

        return response()->json([
            'message' => "Role user berhasil diubah ke {$request->role}",
            'data'    => [
                'id'   => $user->id,
                'name' => $user->name,
                'role' => $user->role,
            ],
        ]);
    }
}