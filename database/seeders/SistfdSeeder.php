<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SistfdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        try {
            DB::beginTransaction();

            $permissions = [
                'sistfd/usuário listar',
                'sistfd/usuário criar',
                'sistfd/usuário editar',
                'sistfd/usuário apagar',
                'sistfd/regra listar',
                'sistfd/regra criar',
                'sistfd/regra editar',
                'sistfd/regra apagar',
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
                'sesadm/sistfd',
            ];

            foreach($roles as $vlr) {
                $module = explode('/',$vlr);
                $permissions = Permission::where('name','LIKE',"%".$module[1]."%")->get();
                Role::create([
                    'name' => $vlr,
                ])->syncPermissions($permissions);
            }

            Module::create([
                'name' => 'sistfd',
                'url' => 'http://localhost:9000',
                'icon' => 'airplane-outline'
            ]);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
        }
    }
}
