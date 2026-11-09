<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

/**
 * Halaman "Akun Saya": setiap pengguna dapat mengganti nama & kata sandinya.
 */
class AkunController extends Controller
{
    public function edit(Request $request): View
    {
        return view('akun.edit', ['user' => $request->user()->load(['programStudi', 'dosen'])]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'current_password.required_with' => 'Masukkan kata sandi saat ini untuk mengganti kata sandi.',
        ], [
            'name' => 'nama',
            'current_password' => 'kata sandi saat ini',
            'password' => 'kata sandi baru',
        ]);

        $request->user()->update(array_filter([
            'name' => $data['name'],
            'password' => $data['password'] ?? null,
        ]));

        return back()->with('success', 'Akun berhasil diperbarui.');
    }
}
