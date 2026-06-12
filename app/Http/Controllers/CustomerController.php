<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        $keyword = trim((string) $request->query('q', ''));
        $customers = Customer::query()
            ->when($keyword !== '', function ($query) use ($keyword) {
                $query->where('nama_customer', 'like', '%'.$keyword.'%')
                    ->orWhere('kode_customer', 'like', '%'.$keyword.'%')
                    ->orWhere('no_telp', 'like', '%'.$keyword.'%');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers', compact('customers'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->check() && auth()->user()->canManageSales(), 403);

        $data = $request->validate([
            'nama_customer' => 'required|string|max:255',
            'kontak_person' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'kota' => 'nullable|string|max:255',
        ]);

        $data['kode_customer'] = 'CUS-'.now()->format('Ymd').'-'.str_pad((string) (Customer::count() + 1), 4, '0', STR_PAD_LEFT);
        $data['status'] = 'aktif';

        Customer::create($data);

        return back()->with('success', 'Customer berhasil ditambahkan.');
    }
}
