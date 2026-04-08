<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    protected $signature = 'admin:reset-password {email} {password}';
    protected $description = 'Reset password admin berdasarkan email';

    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();
        if (!$user) {
            $this->error('User tidak ditemukan: ' . $email);
            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info('Password admin berhasil diubah.');
        return self::SUCCESS;
    }
}
