<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        $roleAdmin = Role::findByName('sesadm/sesadm');
        User::create([
            'name' => 'Admin',
            'email' => 'admin@teste.com',
            'is_valid' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ])->assignRole($roleAdmin);

        User::create([
            'name' => 'Tester',
            'email' => 'teste@teste.com',
            'is_valid' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'User',
            'email' => 'user@teste.com',
            'is_valid' => true,
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
        ]);

        $this->call([
            ModuleSeeder::class,
            ChatSeeder::class,
            ArticleSeeder::class,
            CountySeeder::class,
            // UserSeeder::class,
        ]);
    }
}
