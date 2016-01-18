<?php

use Illuminate\Database\Seeder;

class BundlesMenuTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bundles_menu')->truncate();
        DB::table('bundles_menu')->delete();
        DB::table('bundles_menu')->delete();

        $bundles_menu = [
            [
                'title' => 'Pick your selection',
                'menu_type' => 1,
                'is_root' => 1
            ],
            [
                'title' => 'Daily Internet Bundles',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => '7 Day Bundles',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => 'Buy 90 Day Bundle',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => 'Buy for Other Number',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => 'Okoa Bundles',
                'menu_type' => 1,
                'is_root' => 0
            ],
            //this type of menu should pick the selected menu item from the previous step
            [
                'title' => 'Subscribe to Daily {{name}}',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => 'You have successfully subscribed to {{name}}',
                'menu_type' => 1,
                'is_root' => 0
            ],
            [
                'title' => 'Safaricom, The Better option',
                'menu_type' => 1,
                'is_root' => 0
            ]
        ];

        DB::table('bundles_menu')->insert($bundles_menu);
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
