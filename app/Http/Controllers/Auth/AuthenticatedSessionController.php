<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('/', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Redirect to the login page.
     */

     public function redirectToGoogle()
     {
         return Socialite::driver('google')->redirect();
     }

        /**
        * Handle the Google callback.
        */
    
        public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->user();
    
    // First try to find user by google_id
    $user = User::where('google_id', $googleUser->id)->first();
    
    // If not found, try to find by email
    if (!$user) {
        $user = User::where('email', $googleUser->email)->first();
        
        // If user exists but doesn't have google_id, update it
        if ($user) {
            $user->update([
                'google_id' => $googleUser->id,
            ]);
        }
    }
    
    // If user still not found, create a new one
    if (!$user) {
        $user = User::create([
            'name' => $googleUser->name,
            'email' => $googleUser->email,
            'google_id' => $googleUser->id,
            'email_verified_at' => now(),
        ]);
    }

    Auth::login($user, true);

    return redirect()->intended(route('/'));  // Make sure 'home' is a valid route name
}
}
