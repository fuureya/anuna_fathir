<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

// Create user with email matching reservation
$email = 'kuluzen@gmail.com';

$user = User::where('email', $email)->first();

if (!$user) {
    $user = User::create([
        'name' => 'Ilham Fariqul',
        'email' => $email,
        'password' => bcrypt('password123'),
        'role' => 'user',
    ]);
    echo "✅ User created successfully!\n";
} else {
    echo "✅ User already exists!\n";
}

echo "\nUser Details:\n";
echo "  - ID: {$user->id}\n";
echo "  - Name: {$user->name}\n";
echo "  - Email: {$user->email}\n";
echo "  - Role: {$user->role}\n";
echo "\nLogin credentials:\n";
echo "  - Email: {$user->email}\n";
echo "  - Password: password123\n";
