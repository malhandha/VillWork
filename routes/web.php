<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;

use App\Livewire\Admin\Auth\Login as AdminLogin;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\Pelatihan\Index as AdminPelatihanIndex;
use App\Livewire\Admin\Pelatihan\Form as AdminPelatihanForm;

use App\Livewire\Pengguna\Lamaran\Index as PenggunaLamaranIndex;
use App\Exports\LamaranExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Livewire\Pengguna\Lamaran\Create as PenggunaLamaranCreate;

Route::get('/', function () {
    return view('welcome');
})->name('home');

require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lamaran', PenggunaLamaranIndex::class)->name('pengguna.lamaran.index');
    Route::get('/lamaran/{lowongan}/create', PenggunaLamaranCreate::class)->name('pengguna.lamaran.create');
});

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', AdminLogin::class)->name('login');
    });

    Route::get('/admin/lamaran/export', function () {
        return Excel::download(new LamaranExport, 'data_lamaran.xlsx');
    })->middleware('auth:admin')->name('admin.lamaran.export');

    Route::middleware('auth:admin')->group(function () {
        Route::any('dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('peserta-pelatihan', App\Livewire\Admin\PesertaPelatihan\Index::class)->name('peserta-pelatihan.index');
        Route::any('pelatihan', AdminPelatihanIndex::class)->name('pelatihan.index');
        Route::any('pelatihan/create', AdminPelatihanForm::class)->name('pelatihan.create');
        Route::any('pelatihan/{pelatihan}/edit', AdminPelatihanForm::class)->name('pelatihan.edit');
        Route::any('users', \App\Livewire\Admin\User\Index::class)->name('user.index');
        Route::any('lowongan', \App\Livewire\Admin\Lowongan\Index::class)->name('lowongan.index');
        Route::any('lamaran', \App\Livewire\Admin\Lamaran\Index::class)->name('lamaran.index');

        Route::post('logout', function (Request $request) {
            Auth::guard('admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        })->name('logout');
    });
});