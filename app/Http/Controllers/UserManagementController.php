<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    private function authorizeSuperAdmin(): void
    {
        abort_unless(auth()->check() && auth()->user()->isSuperAdmin(), 403);
    }

    public function index(Request $request)
    {
        $this->authorizeSuperAdmin();
        $keyword = trim((string) $request->query('q', ''));
        $users = User::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%')
                    ->orWhere('email', 'like', '%'.$keyword.'%')
                    ->orWhere('role', 'like', '%'.$keyword.'%');
            })
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    public function store(Request $request)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:superadmin,admin,staff_gudang,staff_penjualan,kurir,customer',
        ]);
        $data['password'] = Hash::make($data['password']);
        DB::transaction(function () use ($data) {
            $user = User::create($data);
            $this->ensureCustomerProfile($user);
        });

        return back()->with('success', 'Akun dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $this->authorizeSuperAdmin();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:superadmin,admin,staff_gudang,staff_penjualan,kurir,customer',
            'password' => 'nullable|string|min:6',
        ]);
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        DB::transaction(function () use ($user, $data) {
            $user->update($data);
            $this->ensureCustomerProfile($user->fresh());
        });

        return back()->with('success', 'Akun diperbarui.');
    }

    public function destroy(User $user)
    {
        $this->authorizeSuperAdmin();
        abort_if(auth()->id() === $user->id, 400, 'Tidak dapat menghapus diri sendiri.');
        $user->delete();

        return back()->with('success', 'Akun dihapus.');
    }

    private function ensureCustomerProfile(User $user): void
    {
        if (! $user->isCustomer()) {
            return;
        }

        Customer::firstOrCreate(
            ['user_id' => $user->id],
            [
                'kode_customer' => 'CUS-'.now()->format('Ymd').'-'.str_pad((string) (Customer::count() + 1), 4, '0', STR_PAD_LEFT),
                'nama_customer' => $user->name,
                'email' => $user->email,
                'status' => 'aktif',
            ]
        );
    }
}
