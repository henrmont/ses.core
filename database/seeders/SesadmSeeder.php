<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SesadmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $permissions = [
                'sesadm/usuário listar',
                'sesadm/usuário criar',
                'sesadm/usuário editar',
                'sesadm/usuário apagar',
                'sesadm/regra listar',
                'sesadm/regra criar',
                'sesadm/regra editar',
                'sesadm/regra apagar',
                'sesadm/sigtap listar',
                'sesadm/sigtap criar',
                'sesadm/sigtap editar',
                'sesadm/sigtap apagar',
                'sesadm/município listar',
                'sesadm/município criar',
                'sesadm/município editar',
                'sesadm/município apagar',
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
                'sesadm/sesadm',
            ];

            foreach($roles as $vlr) {
                $module = explode('/',$vlr);
                $permissions = Permission::where('name','LIKE',"%".$module[1]."%")->get();
                Role::create([
                    'name' => $vlr,
                ])->syncPermissions($permissions);
            }

            Module::create([
                'name' => 'sesadm',
                'url' => 'http://localhost:8000',
                'icon' => 'settings-outline'
            ]);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        }
    }
}
