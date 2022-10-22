<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModulesUrl;
use Illuminate\Database\Seeder;

class ModuleUrlSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'm_id' => 4, 'url' => '/admin/setting/module/group', 'status' => '1', 'type' => '0', 'name' => 'admin.modules_groups.index', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 2, 'm_id' => 4, 'url' => '/admin/setting/module/group/create', 'status' => '1', 'type' => '1', 'name' => 'admin.modules_groups.create', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 3, 'm_id' => 4, 'url' => '/admin/setting/module/group/edit', 'status' => '1', 'type' => '1', 'name' => 'admin.modules_groups.edit', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 4, 'm_id' => 4, 'url' => '/admin/setting/module/group/delete', 'status' => '1', 'type' => '1', 'name' => 'admin.modules_groups.delete', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 5, 'm_id' => 6, 'url' => '/admin/setting/role/{id}/permission/create', 'status' => '1', 'type' => '1', 'name' => 'admin.role.permission.create', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 6, 'm_id' => 6, 'url' => '/admin/setting/role/{id}/permission/edit', 'status' => '1', 'type' => '1', 'name' => 'admin.role.permission.edit', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 7, 'm_id' => 5, 'url' => '/admin/setting/module/{id}/url', 'status' => '1', 'type' => '1', 'name' => 'admin.module.url.index', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 8, 'm_id' => 5, 'url' => '/admin/setting/module/{id}/url/create', 'status' => '1', 'type' => '1', 'name' => 'admin.module.url.create', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 9, 'm_id' => 5, 'url' => '/admin/setting/module/{id}/url/edit', 'status' => '1', 'type' => '1', 'name' => 'admin.module.url.edit', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 10, 'm_id' => 5, 'url' => '/admin/setting/module/{id}/url/delete', 'status' => '1', 'type' => '1', 'name' => 'admin.module.url.delete', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 11, 'm_id' => 3, 'url' => '/admin/setting/module', 'status' => '1', 'type' => '0', 'name' => 'admin.module.index', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 12, 'm_id' => 3, 'url' => '/admin/setting/module/create', 'status' => '1', 'type' => '1', 'name' => 'admin.module.create', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 13, 'm_id' => 3, 'url' => '/admin/setting/module/edit', 'status' => '1', 'type' => '1', 'name' => 'admin.module.edit', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 14, 'm_id' => 3, 'url' => '/admin/setting/module/delete', 'status' => '1', 'type' => '1', 'name' => 'admin.module.delete', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 15, 'm_id' => 2, 'url' => '/admin/setting/role', 'status' => '1', 'type' => '0', 'name' => 'admin.role.index', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 16, 'm_id' => 2, 'url' => '/admin/setting/role/create', 'status' => '1', 'type' => '1', 'name' => 'admin.role.create', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 17, 'm_id' => 2, 'url' => '/admin/setting/role/edit', 'status' => '1', 'type' => '1', 'name' => 'admin.role.edit', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 18, 'm_id' => 2, 'url' => '/admin/setting/role/delete', 'status' => '1', 'type' => '1', 'name' => 'admin.role.delete', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 19, 'm_id' => 1, 'url' => '/admin/dashboard', 'status' => '1', 'type' => '0', 'name' => 'admin.dashboard', 'created_at' => NULL, 'updated_at' => NULL],

        ];
        ModulesUrl::insert($data);
    }
}
