<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class InstallationRequest extends FormRequest
{
    public function rules()
    {
        return [
            'db_name' => 'required|string|max:64',
            'db_user' => 'required|string|max:32',
            'admin_email' => 'required|email|unique:users,email',
            'site_url' => 'required|url',
        ];
    }
}
