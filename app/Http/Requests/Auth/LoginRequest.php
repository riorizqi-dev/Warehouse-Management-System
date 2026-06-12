<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'login' => ['required', 'string'], // username atau email
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $login = $this->input('login');
        $password = $this->input('password');

        $adminEmail = config('admin.email');
        $adminPassword = config('admin.password');

        Log::info('Login attempt', [
            'login' => $login,
            'admin_email' => $adminEmail,
            'is_admin_email' => $login === $adminEmail,
        ]);

        if ($login === $adminEmail && $password === $adminPassword) {
            $adminUser = \App\Models\User::where('email', $adminEmail)->first();

            if (! $adminUser) {
                $adminUser = \App\Models\User::create([
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => $adminEmail,
                    'password' => bcrypt($adminPassword),
                    'role' => 'admin',
                    'email_verified_at' => now(),
                ]);
            } elseif (empty($adminUser->email_verified_at)) {
                $adminUser->forceFill(['email_verified_at' => now()])->save();
            }

            Auth::login($adminUser, $this->boolean('remember'));
            RateLimiter::clear($this->throttleKey());

            return;
        }

        $user = \App\Models\User::where(function ($query) use ($login) {
            $query->where('email', $login)
                ->orWhere('username', $login);
        })->first();

        if (! $user || ! \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')).'|'.$this->ip());
    }
}
