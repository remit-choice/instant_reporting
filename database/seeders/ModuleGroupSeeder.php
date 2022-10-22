<?php

namespace Database\Seeders;

use App\Models\ModulesGroup;
use Illuminate\Database\Seeder;

class ModuleGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'name' => 'Dashboard', 'icon' => 'fas fa-tachometer-alt', 'status' => 1, 'sort' => 1, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 2, 'name' => 'Setting', 'icon' => 'fas fa-cogs', 'status' => 1, 'sort' => 2, 'created_at' => NULL, 'updated_at' => NULL],
        ];
        ModulesGroup::insert($data);
    }
}
