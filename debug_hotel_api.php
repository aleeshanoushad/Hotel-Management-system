<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check if user exists
$user = \App\Models\User::first();
echo 'User exists: ' . ($user ? 'YES' : 'NO') . PHP_EOL;

if ($user) {
    echo 'User email: ' . $user->email . PHP_EOL;
    
    // Create token
    $token = $user->createToken('test-token')->plainTextToken;
    echo 'Token created: ' . substr($token, 0, 20) . '...' . PHP_EOL;
    
    // Test hotel query
    $hotels = \App\Models\Hotel::with(['city', 'country'])->withCount('rooms')->paginate(15);
    echo 'Hotels count: ' . $hotels->count() . PHP_EOL;
    echo 'Total hotels: ' . $hotels->total() . PHP_EOL;
    
    if ($hotels->count() > 0) {
        echo 'First hotel: ' . $hotels->first()->name . PHP_EOL;
    }
}
