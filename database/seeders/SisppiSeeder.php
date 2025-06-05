<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SisppiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $permissions = [
                'sisppi/usuário listar',
                'sisppi/usuário criar',
                'sisppi/usuário editar',
                'sisppi/usuário apagar',
                'sisppi/regra listar',
                'sisppi/regra criar',
                'sisppi/regra editar',
                'sisppi/regra apagar',
                'sisppi/município listar',
                'sisppi/município criar',
                'sisppi/município editar',
                'sisppi/município apagar',
            ];

            foreach($permissions as $vlr) {
                $permission = Permission::create([
                    'name'  => $vlr,
                    'guard_name' => 'api',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            $roles = [
                'sesadm/sisppi',
            ];

            foreach($roles as $vlr) {
                $module = explode('/',$vlr);
                $permissions = Permission::where('name','LIKE',"%".$module[1]."%")->get();
                Role::create([
                    'name' => $vlr,
                ])->syncPermissions($permissions);
            }

            Module::create([
                'name' => 'sisppi',
                'url' => 'http://localhost:10000',
                'icon' => 'wallet-outline'
            ]);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        }
    }
}
