<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

//        User::factory()->create([
//            'name' => 'Test User',
//            'email' => 'test@example.com',
//        ]);
        // Buat Role
        $roles = ['admin', 'pembimbing', 'kepala_divisi', 'mahasiswa'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Buat User + Assign Role
        $admin = User::where('name', 'admin')->first();
        $admin->assignRole('admin');

        $pembimbing = User::where('name', 'pembimbing')->first();
        $pembimbing->assignRole('pembimbing');

        $kepala = User::where('name', 'kepala divisi')->first();
        $kepala->assignRole('kepala_divisi');

        $mahasiswa = User::where('name', 'mahasiswa')->first();
        $mahasiswa->assignRole('mahasiswa');
    }
}
