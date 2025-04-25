<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $users = $query->get()->map(function ($user) {
            return $this->transformUserData($user);
        });

        return response()->json($users);
    }

    // Menampilkan detail user berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($this->transformUserData($user));
    }

    // Helper method untuk transformasi data user
    protected function transformUserData(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'profile_photo_url' => $this->getProfilePhotoUrl($user),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at
        ];
    }

    // Helper method untuk mendapatkan URL foto profil
    protected function getProfilePhotoUrl(User $user)
    {
        if (!$user->photo_profile) {
            return null;
        }

        // Jika foto profil adalah URL eksternal
        if (filter_var($user->photo_profile, FILTER_VALIDATE_URL)) {
            return $user->photo_profile;
        }

        // Jika foto profil disimpan di storage lokal
        return Storage::url($user->photo_profile);
    }
}