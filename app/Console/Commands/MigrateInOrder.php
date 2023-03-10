<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateInOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate_in_order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the migrations in the order specified in the file app/Console/Comands/MigrateInOrder.php \n Drop all the table in db before execute the command.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** Specify the names of the migrations files in the order you want to 
         * loaded
         * $migrations =[ 
         *               'xxxx_xx_xx_000000_create_nameTable_table.php',
         *    ];
         */
        $migrations = [
            '2022_10_12_155342_create_modules_groups_table.php',
            '2022_10_12_154944_create_roles_table.php',
            '2022_10_12_155306_create_modules_table.php',
            '2022_10_12_155441_create_modules_urls_table.php',
            '2022_10_12_155525_create_permissions_table.php',
            '2014_10_12_000000_create_users_table.php',
            '2014_10_12_100000_create_password_resets_table.php',
            '2019_08_19_000000_create_failed_jobs_table.php',
            '2019_12_14_000001_create_personal_access_tokens_table.php',
            '2022_10_13_092422_create_currencies_table.php',
            '2022_11_10_084320_create_currencies_receiving_countries_table.php',
            '2022_10_21_121101_create_currencies_rates_table.php',
            '2022_10_13_122848_create_transactions_data_table.php',
            '2022_10_20_130918_create_online_customers_table.php',
            '2023_01_26_083349_create_buyers_table.php',
            '2023_01_26_091156_create_payment_methods_table.php',
            '2023_01_26_083754_create_buyers_payment_methods_table.php'
        ];

        foreach ($migrations as $migration) {
            $basePath = 'database/migrations/';
            $migrationName = trim($migration);
            $path = $basePath . $migrationName;
            $this->call('migrate:refresh', [
                '--path' => $path,
            ]);
        }
    }
}
