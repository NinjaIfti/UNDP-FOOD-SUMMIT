<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

$email = 'admin@gcfs.com';
$user = User::where('email', $email)->first();

if (!$user) {
    $user = User::create([
        'name' => 'Super Admin',
        'email' => $email,
        'password' => bcrypt(\Illuminate\Support\Str::random(32)),
    ]);
}

Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
$user->syncRoles(['super-admin']);

echo "Done: {$user->email} is now super-admin.\n";
