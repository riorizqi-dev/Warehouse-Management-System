<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FaqController extends Controller
{
    public function index()
    {
        $this->authorizeAdmin();

        if (! Schema::hasTable('faqs')) {
            return back()->withErrors(['faq' => 'Tabel FAQ belum ada. Jalankan migrasi database terlebih dahulu.']);
        }

        $faqs = Faq::query()->orderBy('sort_order')->orderBy('id')->paginate(15);

        return view('admin.faqs', compact('faqs'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        if (! Schema::hasTable('faqs')) {
            return back()->withErrors(['faq' => 'Tabel FAQ belum ada. Jalankan migrasi database terlebih dahulu.']);
        }

        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        Faq::create($data);

        return back()->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function update(Request $request, Faq $faq)
    {
        $this->authorizeAdmin();

        if (! Schema::hasTable('faqs')) {
            return back()->withErrors(['faq' => 'Tabel FAQ belum ada. Jalankan migrasi database terlebih dahulu.']);
        }

        $data = $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'keywords' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = (int) ($data['sort_order'] ?? 0);

        $faq->update($data);

        return back()->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(Faq $faq)
    {
        $this->authorizeAdmin();

        if (! Schema::hasTable('faqs')) {
            return back()->withErrors(['faq' => 'Tabel FAQ belum ada. Jalankan migrasi database terlebih dahulu.']);
        }

        $faq->delete();

        return back()->with('success', 'FAQ berhasil dihapus.');
    }

    private function authorizeAdmin(): void
    {
        abort_unless(
            auth()->check() && (
                auth()->user()->isAdmin() ||
                auth()->user()->isSuperAdmin() ||
                auth()->user()->isSalesStaff()
            ),
            403
        );
    }
}
