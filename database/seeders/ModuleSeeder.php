<?php

namespace Database\Seeders;

use App\Models\Module;
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
        Module::create([
            'm_g_id' => 1,
            'name' => "Dashboard",
            'icon' => "ten",
            'type' => "0",
            'sort' => 0,
            'status' => 0
        ]);
    }
}
