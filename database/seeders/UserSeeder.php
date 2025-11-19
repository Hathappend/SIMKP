<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'admin']);

        $admin = User::create([
            'name'        => 'Administrator',
            'email'       => 'admin@example.com',
            'password'    => Hash::make('password123'),
            'division_id' => 1,
        ]);

        // Assign role ke admin
        $admin->assignRole($role);
    }
}
