<?php

use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\DataBarangController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FeaturePageController;
use App\Http\Controllers\FeatureToggleController;
use App\Http\Controllers\InventoryLogController;
use App\Http\Controllers\KategoriBarangController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::post('/chatbot/ask', [ChatbotController::class, 'ask'])
        ->name('chatbot.ask')
        ->middleware('throttle:30,1');

    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user->isCustomer()) {
            return redirect()->route('customer.dashboard');
        }
        if ($user->isSuperAdmin()) {
            return view('dashboard');
        }
        if ($user->isAdmin()) {
            return view('dashboard');
        }
        if ($user->canManageSales()) {
            return redirect()->route('orders.index');
        }
        if ($user->canManageWarehouse()) {
            return redirect()->route('orders.index', ['status' => 'reserved']);
        }
        if ($user->canManageShipping()) {
            return redirect()->route('orders.index', ['status' => 'packed']);
        }
        if (feature_enabled('data-barang') && $user->canManageMasterData()) {
            return redirect()->route('barang.index');
        }
        if (feature_enabled('kategori-barang') && $user->canManageMasterData()) {
            return redirect()->route('kategori.barang');
        }
        if (feature_enabled('laporan-barang') && $user->canManageMasterData()) {
            return redirect()->route('laporan.barang');
        }

        return redirect()->route('pengaturan');
    })->name('dashboard');

    Route::resource('barang', DataBarangController::class)
        ->except(['show'])
        ->middleware(['feature:data-barang', 'role:superadmin,admin,staff_gudang']);
    Route::post('/barang/{id}/stock-out', [DataBarangController::class, 'stockOut'])
        ->name('barang.stock-out')
        ->middleware(['feature:data-barang', 'role:superadmin,admin,staff_gudang']);

    Route::get('/kategori-barang', [KategoriBarangController::class, 'index'])->name('kategori.barang')->middleware(['feature:kategori-barang', 'role:superadmin,admin,staff_gudang,staff_penjualan']);
    Route::post('/kategori-barang', [KategoriBarangController::class, 'store'])->name('kategori.store')->middleware(['feature:kategori-barang', 'role:superadmin,admin']);
    Route::put('/kategori-barang/{id}', [KategoriBarangController::class, 'update'])->name('kategori.update')->middleware(['feature:kategori-barang', 'role:superadmin,admin']);
    Route::delete('/kategori-barang/{id}', [KategoriBarangController::class, 'destroy'])->name('kategori.destroy')->middleware(['feature:kategori-barang', 'role:superadmin,admin']);
    Route::get('/laporan-barang', [LaporanController::class, 'index'])->name('laporan.barang')->middleware(['feature:laporan-barang', 'role:superadmin,admin,staff_gudang,staff_penjualan']);
    Route::view('/pengaturan', 'pengaturan')->name('pengaturan');

    Route::get('/features', [FeatureToggleController::class, 'index'])->name('features.index')->middleware('role:superadmin');
    Route::post('/features', [FeatureToggleController::class, 'store'])->name('features.store')->middleware('role:superadmin');
    Route::put('/features/{feature}', [FeatureToggleController::class, 'update'])->name('features.update')->middleware('role:superadmin');
    Route::delete('/features/{feature}', [FeatureToggleController::class, 'destroy'])->name('features.destroy')->middleware('role:superadmin');

    Route::get('/api/features/enabled', [FeatureToggleController::class, 'getEnabledFeatures'])->name('features.enabled');

    Route::get('/feature/{key}', [FeaturePageController::class, 'show'])->name('feature.show')->where('key', '[a-z0-9_-]+');

    Route::get('/inventory-logs', [InventoryLogController::class, 'index'])->name('inventory.logs')->middleware('role:superadmin,admin,staff_gudang');

    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store')->middleware('role:superadmin,admin,staff_penjualan');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index')->middleware('role:superadmin,admin,staff_gudang,staff_penjualan,kurir');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/orders/{order}/approve', [OrderController::class, 'approve'])->name('orders.approve')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/orders/{order}/reject', [OrderController::class, 'reject'])->name('orders.reject')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/orders/{order}/payment-verify', [OrderController::class, 'verifyPayment'])->name('orders.payment.verify')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/orders/{order}/payment-reject', [OrderController::class, 'rejectPayment'])->name('orders.payment.reject')->middleware('role:superadmin,admin,staff_penjualan');
    Route::get('/orders/{order}/payment-proof/view', [OrderController::class, 'viewPaymentProof'])->name('orders.payment.proof.view')->middleware('role:superadmin,admin,staff_penjualan');
    Route::delete('/orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/orders/{order}/picking', [OrderController::class, 'startPicking'])->name('orders.picking')->middleware('role:superadmin,admin,staff_gudang');
    Route::post('/orders/{order}/pack', [OrderController::class, 'markPacked'])->name('orders.pack')->middleware('role:superadmin,admin,staff_gudang');
    Route::post('/orders/{order}/ship', [OrderController::class, 'markShipped'])->name('orders.ship')->middleware('role:superadmin,admin,kurir');

    Route::get('/admin/users', [UserManagementController::class, 'index'])->name('admin.users.index')->middleware('role:superadmin');
    Route::post('/admin/users', [UserManagementController::class, 'store'])->name('admin.users.store')->middleware('role:superadmin');
    Route::put('/admin/users/{user}', [UserManagementController::class, 'update'])->name('admin.users.update')->middleware('role:superadmin');
    Route::delete('/admin/users/{user}', [UserManagementController::class, 'destroy'])->name('admin.users.destroy')->middleware('role:superadmin');

    Route::get('/admin/faqs', [FaqController::class, 'index'])->name('admin.faqs.index')->middleware('role:superadmin,admin,staff_penjualan');
    Route::post('/admin/faqs', [FaqController::class, 'store'])->name('admin.faqs.store')->middleware('role:superadmin,admin,staff_penjualan');
    Route::put('/admin/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update')->middleware('role:superadmin,admin,staff_penjualan');
    Route::delete('/admin/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy')->middleware('role:superadmin,admin,staff_penjualan');
});

Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('/catalog', [CustomerPortalController::class, 'catalog'])->name('catalog');
    Route::get('/catalog/{barang}', [CustomerPortalController::class, 'product'])->name('catalog.show');

    Route::get('/cart', [CustomerPortalController::class, 'cart'])->name('cart');
    Route::post('/cart/{barang}', [CustomerPortalController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{barang}', [CustomerPortalController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/{barang}', [CustomerPortalController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/checkout', [CustomerPortalController::class, 'checkout'])->name('checkout');

    Route::get('/orders', [CustomerPortalController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [CustomerPortalController::class, 'orderDetail'])->name('orders.show');
    Route::post('/orders/{order}/payment-proof', [CustomerPortalController::class, 'uploadPaymentProof'])->name('orders.payment-proof');
    Route::get('/orders/{order}/payment-proof/view', [CustomerPortalController::class, 'viewPaymentProof'])->name('orders.payment-proof.view');
    Route::post('/orders/{order}/confirm-received', [CustomerPortalController::class, 'confirmReceived'])->name('orders.confirm-received');
});

require __DIR__.'/auth.php';
