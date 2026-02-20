<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// Find user
$user = User::where('username', 'admin')->first();
echo "1. User found: " . ($user ? $user->name : 'NOT FOUND') . "\n";

// Check password
if ($user) {
    $passwordMatch = Hash::check('password', $user->password);
    echo "2. Password match: " . ($passwordMatch ? 'YES' : 'NO') . "\n";
    
    // Try login
    Auth::login($user);
    echo "3. Auth::check() after login: " . (Auth::check() ? 'YES' : 'NO') . "\n";
    echo "4. Authenticated user ID: " . (Auth::id() ?? 'NULL') . "\n";
    echo "5. Authenticated user name: " . (Auth::user()?->name ?? 'NULL') . "\n";
}
?>
