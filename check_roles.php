<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::with('roles')->get();
$output = "Total Users: " . count($users) . "\n";
foreach ($users as $user) {
    $roleNames = $user->roles->pluck('name')->toArray();
    $output .= "ID: {$user->id} | Email: {$user->email} | Roles: [" . implode(', ', $roleNames) . "]\n";
}

$roles = \App\Models\Role::all();
$output .= "\nAvailable Roles:\n";
foreach ($roles as $role) {
    $output .= "ID: {$role->id} | Name: {$role->name} | Slug: {$role->slug}\n";
}

file_put_contents('roles_output_v3.txt', $output);
echo "Output written to roles_output_v3.txt\n";
