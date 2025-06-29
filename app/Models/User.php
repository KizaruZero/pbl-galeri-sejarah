<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Support\Facades\Auth;
use Filament\Panel;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Filament\Notifications\Notification;




class User extends Authenticatable implements FilamentUser, MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_profile',
        'google_id',
        'status',
        'last_login_at',
        'last_login_ip',
        'last_user_agent',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        $allowedRoles = ['super_admin', 'direktur', 'author']; // Roles yang diizinkan
        return $this->roles->pluck('name')->intersect($allowedRoles)->isNotEmpty();
    }

    // roles
    // public function role()
    // {
    //     return $this->belongsToMany(Role::class);
    // }


    // notifiable_type

}