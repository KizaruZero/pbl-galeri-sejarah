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
use Illuminate\Validation\ValidationException;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Views/LoginPage', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {

        $userEmail = $request->email;

        $user = User::where('email', $userEmail)->first();
        if ($user && is_null($user->password)) {
            throw ValidationException::withMessages([
                'email' => 'This email is registered with Google. Please login with Google.',
            ]);
        }

        if ($user->status == 'inactive') {
            throw ValidationException::withMessages([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

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
        try {

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
                    'password' => bcrypt(Str::random(16)), // Generate a random password
                    'email_verified_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('Google OAuth Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'google' => 'Failed to authenticate with Google. Please try again.'
            ]);
        }
        Auth::login($user, true);
        return redirect()->intended(route('/'));  // Make sure 'home' is a valid route name
    }
}
