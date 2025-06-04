<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModuleUser;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Module::create([
            'name' => 'sesadm',
            'url' => 'http://localhost:8000',
            'icon' => 'settings-outline'
        ]);

        Module::create([
            'name' => 'sisppi',
            'url' => 'http://localhost:10000',
            'icon' => 'wallet-outline'
        ]);

        Module::create([
            'name' => 'sistfd',
            'url' => 'http://localhost:9000',
            'icon' => 'airplane-outline'
        ]);

        ModuleUser::create([
            'user_id' => 1,
            'module_id' => 1
        ]);

        ModuleUser::create([
            'user_id' => 1,
            'module_id' => 3
        ]);

        ModuleUser::create([
            'user_id' => 2,
            'module_id' => 2
        ]);

        ModuleUser::create([
            'user_id' => 2,
            'module_id' => 3
        ]);
    }
}
