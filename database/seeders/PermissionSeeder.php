<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ['id' => 1, 'r_id' => 1, 'm_id' => 1, 'view' => 1, 'add' => NULL, 'edit' => NULL, 'delete' => NULL, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 2, 'r_id' => 1, 'm_id' => 2, 'view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 3, 'r_id' => 1, 'm_id' => 3, 'view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 4, 'r_id' => 1, 'm_id' => 4, 'view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 5, 'r_id' => 1, 'm_id' => 5, 'view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'created_at' => NULL, 'updated_at' => NULL],
            ['id' => 6, 'r_id' => 1, 'm_id' => 6, 'view' => 1, 'add' => 1, 'edit' => 1, 'delete' => 1, 'created_at' => NULL, 'updated_at' => NULL],
        ];
        Permission::insert($data);
    }
}
