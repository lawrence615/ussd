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
        DB::table('bundles_menu')->delete();

        $bundles_menu = [
            [
                'title' => 'Pick your selection',
                'menu_type' => 1,
                'is_root' => 1
            ],
            [
                'title' => 'Daily Internet Bundles',
                'menu_type' => 2,
                'is_root' => 0
            ],
            [
                'title' => '7 Day Bundles',
                'menu_type' => 2,
                'is_root' => 0
            ],
            [
                'title' => 'Buy 90 Day Bundle',
                'menu_type' => 2,
                'is_root' => 0
            ],
            [
                'title' => 'Buy for Other Number',
                'menu_type' => 2,
                'is_root' => 0
            ],
            [
                'title' => 'Okoa Bundles',
                'menu_type' => 2,
                'is_root' => 0
            ],
            [
                'title' => 'Subscribe to Daily',
                'menu_type' => 2,
                'is_root' => 0
            ]
        ];

        DB::table('bundles_menu')->insert($bundles_menu);
    }
}
