<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordsSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('email', 'not like', '%@example.com')->get();
        foreach ($users as $user) {
            $user->password = Hash::make('1234');
            $user->save();
        }
        $this->command->info("Updated " . $users->count() . " users.");
    }
}
