<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
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
                'sesadm/sistfd',
                'sesadm/sisppi',
            ];

            foreach($roles as $vlr) {
                $module = explode('/',$vlr);
                $permissions = Permission::where('name','LIKE',"%".$module[1]."%")->get();
                Role::create([
                    'name' => $vlr,
                ])->syncPermissions($permissions);
            }

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        }

    }
}
