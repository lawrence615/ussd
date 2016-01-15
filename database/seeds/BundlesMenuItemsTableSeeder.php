<?php

use Illuminate\Database\Seeder;

class BundlesMenuItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bundles_menu_items')->delete();

        $bundles_menu_items = [
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Daily Bundle'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Buy 7 Day Bundle'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Buy 30 Day Bundle'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Buy 90 Day Bundle'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Buy for Other Number'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Okoa Bundles'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Check Bundle Balance'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Facebook'
            ],
            [
                'menu_id' => 1,
                'next_menu_id' => 2,
                'description' => 'Appstore'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => '150MB + 150 SMS @ Sh50'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => '60MB + 60 SMS @ Sh30'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => '35MB + 35 SMS @ Sh20'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => '15MB + 15 SMS @ Sh10'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => '7MB + 7 SMS @ Sh5'
            ],
            [
                'menu_id' => 2,
                'next_menu_id' => 2,
                'description' => 'Unsubscribe'
            ]
        ];

        DB::table('bundles_menu_items')->insert($bundles_menu_items);
    }
}
