<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\ModulesGroup;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'm_g_id' => 1, 'name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt nav-icon', 'sort' => NULL, 'status' => '1', 'type' => '0', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 2, 'm_g_id' => 2, 'name' => 'Roles', 'icon' => 'fas fa-user-secret nav-icon', 'sort' => NULL, 'status' => '1', 'type' => '0', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 3, 'm_g_id' => 2, 'name' => 'Modules', 'icon' => 'fas fa-object-ungroup', 'sort' => NULL, 'status' => '1', 'type' => '0', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 4, 'm_g_id' => 2, 'name' => 'Module Groups', 'icon' => 'fas fa-object-group nav-icon', 'sort' => NULL, 'status' => '1', 'type' => '0', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 5, 'm_g_id' => 2, 'name' => 'Modules URL', 'icon' => 'fas fa-link', 'sort' => NULL, 'status' => '1', 'type' => '1', 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 6, 'm_g_id' => 2, 'name' => 'Permissions', 'icon' => 'fas fa-unlock-alt', 'sort' => NULL, 'status' => '1', 'type' => '1', 'created_at' => NULL, 'updated_at' => NULL],
        ];
        Module::insert($data);
    }
}
