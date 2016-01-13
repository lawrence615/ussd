<?php

use Illuminate\Database\Seeder;

class KplcStaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kplc_staff')->delete();
        $kplc_staff = [
            [
                'first_name' => 'Lawrence',
                'last_name' => 'Macharia',
                'staff_id' => 'ekp1111'
            ], [
                'first_name' => 'Leonard',
                'last_name' => 'Korir',
                'staff_id' => 'ekp1112'
            ], [
                'first_name' => 'Moses',
                'last_name' => 'Kioko',
                'staff_id' => 'ekp1113'
            ], [
                'first_name' => 'Joseph',
                'last_name' => 'Murgor',
                'staff_id' => 'ekp1114'
            ],
            [
                'first_name' => 'Joseph',
                'last_name' => 'Langat',
                'staff_id' => 'ekp1115'
            ],
        ];
        DB::table('kplc_staff')->insert($kplc_staff);
    }
}
