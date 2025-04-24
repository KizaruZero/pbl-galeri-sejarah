<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Menampilkan daftar user (bisa difilter berdasarkan role)
    public function index(Request $request)
    {
        $query = User::query();

        // Filter berdasarkan role jika ada
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return response()->json($users);
    }

    // Menampilkan detail user berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }
}
