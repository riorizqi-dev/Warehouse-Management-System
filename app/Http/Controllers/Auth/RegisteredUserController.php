<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'no_telp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string', 'max:1000'],
            'kota' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request) {
            $baseUsername = Str::slug(Str::before($request->email, '@'), '_');
            if ($baseUsername === '') {
                $baseUsername = 'customer';
            }
            $username = $baseUsername;
            $counter = 1;
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername.'_'.$counter++;
            }

            $created = User::create([
                'name' => $request->name,
                'username' => $username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'email_verified_at' => now(),
            ]);

            Customer::create([
                'user_id' => $created->id,
                'kode_customer' => 'CUS-'.now()->format('Ymd').'-'.str_pad((string) (Customer::count() + 1), 4, '0', STR_PAD_LEFT),
                'nama_customer' => $request->name,
                'kontak_person' => $request->name,
                'no_telp' => $request->no_telp,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'status' => 'aktif',
            ]);

            return $created;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('customer.dashboard');
    }
}
