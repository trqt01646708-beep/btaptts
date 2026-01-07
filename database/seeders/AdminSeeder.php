<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Use full namespace for models
        $adminRole = \App\Models\Role::firstOrCreate(['name' => 'admin', 'display_name' => 'Administrator']);
        $userRole = \App\Models\Role::firstOrCreate(['name' => 'user', 'display_name' => 'Regular User']);

        $permissions = [
            'manage-posts',
            'manage-products',
            'manage-users',
            'manage-orders',
        ];

        foreach ($permissions as $p) {
            $perm = \App\Models\Permission::firstOrCreate(['name' => $p]);
            $adminRole->permissions()->syncWithoutDetaching($perm->id);
        }

        $admin = \App\Models\User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'admin123',
                'password' => \Illuminate\Support\Facades\Hash::make('1234'),
            ]
        );

        $admin->roles()->syncWithoutDetaching($adminRole->id);

        $adminExample = \App\Models\User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin_example',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $adminExample->roles()->syncWithoutDetaching($adminRole->id);

        $thanh = \App\Models\User::updateOrCreate(
            ['email' => 'thanhcong7102004@gmail.com'],
            [
                'name' => 'thanh123',
                'password' => \Illuminate\Support\Facades\Hash::make('1234'),
            ]
        );
        $thanh->roles()->syncWithoutDetaching($userRole->id);

        $user = \App\Models\User::firstOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'user',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $user->roles()->syncWithoutDetaching($userRole->id);
    }
}
