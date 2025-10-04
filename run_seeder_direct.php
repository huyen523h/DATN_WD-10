<?php
// Script chạy seeder trực tiếp mà không cần artisan
require_once 'vendor/autoload.php';

// Load Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "🚀 Bắt đầu chạy seeder...\n\n";
    
    // Chạy từng seeder một cách thủ công
    echo "1. Chạy RoleSeeder...\n";
    $roleSeeder = new Database\Seeders\RoleSeeder();
    $roleSeeder->run();
    echo "✅ RoleSeeder hoàn thành\n\n";
    
    echo "2. Chạy CategorySeeder...\n";
    $categorySeeder = new Database\Seeders\CategorySeeder();
    $categorySeeder->run();
    echo "✅ CategorySeeder hoàn thành\n\n";
    
    echo "3. Chạy UserSeeder...\n";
    $userSeeder = new Database\Seeders\UserSeeder();
    $userSeeder->run();
    echo "✅ UserSeeder hoàn thành\n\n";
    
    echo "4. Chạy TourSeeder...\n";
    $tourSeeder = new Database\Seeders\TourSeeder();
    $tourSeeder->run();
    echo "✅ TourSeeder hoàn thành\n\n";
    
    echo "🎉 Tất cả seeder đã chạy thành công!\n";
    
    // Kiểm tra dữ liệu
    echo "\n📊 Kiểm tra dữ liệu:\n";
    $tours = \App\Models\Tour::count();
    $users = \App\Models\User::count();
    $roles = \App\Models\Role::count();
    $categories = \App\Models\Category::count();
    
    echo "- Tours: $tours\n";
    echo "- Users: $users\n";
    echo "- Roles: $roles\n";
    echo "- Categories: $categories\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
