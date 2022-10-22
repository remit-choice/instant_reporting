<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Module;
use App\Models\ModulesGroup;
use App\Models\ModulesUrl;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::create([
            'id' => 1,
            'name' => "Admin",
            'status' => 1
        ]);
        User::create([
            'r_id' => 1,
            'full_name' => "Azam Naveed",
            'user_name' => "azam",
            'email' => "admin@admin.com",
            'password' => '$2y$10$NZXJhgO/0sVmNsjVeVZCZ.9BI.niwXKONV/HzwOfMNPdj8M6Sg2De',
            'designation' => "Manager",
            'status' => 0
        ]);
        ModulesGroup::create([
            'name' => "Dashboard",
            'icon' => "ten",
            'status' => 0
        ]);
        Module::create([
            'm_g_id' => 1,
            'name' => "Dashboard",
            'icon' => "ten",
            'type' => "0",
            'sort' => 0,
            'status' => 0
        ]);
        // ModulesUrl::create([
        //     'm_g_id' => 1,
        //     'name' => "Dashboard",
        //     'icon' => "ten",
        //     'type' => "0",
        //     'sort' => 0,
        //     'status' => 0
        // ]);
    }
}
