<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$email = 'admin@zapateria.com';

if (!User::where('email', $email)->exists()) {
    User::create([
        'name' => 'Administrador',
        'email' => $email,
        'password' => Hash::make('password'),
    ]);
    echo "Admin user created successfully.\nEmail: $email\nPassword: password";
} else {
    echo "Admin user already exists.";
}
