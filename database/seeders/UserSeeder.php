<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::insert([
            'r_id' => 1,
            'full_name' => "Azam Naveed",
            'user_name' => "azam",
            'email' => "admin@admin.com",
            'password' => '$2y$10$NZXJhgO/0sVmNsjVeVZCZ.9BI.niwXKONV/HzwOfMNPdj8M6Sg2De',
            'designation' => "Admin",
            'status' => 0
        ]);
    }
}
