<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],

        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:' . User::class,
        ],

        'email_confirmation' => [
            'required',
            'same:email'
        ],

        'password' => [
            'required',
            'confirmed',
            Rules\Password::defaults()
        ],
    ], [
        'name.required' => 'Nama lengkap harus diisi.',
        'name.max' => 'Nama lengkap maksimal 255 karakter.',

        'email.required' => 'Email harus diisi.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email sudah terdaftar. Gunakan email lain.',
        'email.confirmed' => 'Email tidak sama dengan konfirmasi email.', // <-- TAMBAHKAN INI
        'email_confirmation.same' => 'Email tidak sama dengan konfirmasi email.',


        'password.required' => 'Password harus diisi.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
    ]);

    try {
        $user = User::create([
            'nik' => 'USER' . time(),
            'fullname' => $request->name,
            'tempat_tanggal_lahir' => now()->subYears(20)->format('Y-m-d'),
            'alamat_tinggal' => 'Belum diisi',
            'pendidikan_terakhir' => 'Belum diisi',
            'jenis_kelamin' => 'Laki-laki',
            'pekerjaan' => 'Belum diisi',
            'usia' => 20,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false))
            ->with('success', 'Selamat datang! Akun Anda berhasil dibuat. Silakan lengkapi profil Anda.');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat membuat akun.');
    }
}

    }
