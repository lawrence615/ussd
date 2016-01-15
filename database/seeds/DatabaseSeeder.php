<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call(KplcStaffTableSeeder::class);
         $this->call(BundlesMenuTableSeeder::class);
         $this->call(BundlesMenuItemsTableSeeder::class);
    }
}
