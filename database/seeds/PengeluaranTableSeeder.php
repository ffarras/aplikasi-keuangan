<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PengeluaranTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
 
    	for($i = 1; $i <= 40; $i++) {

        DB::table('pengeluaran')->insert([
            'user_id' => '1',
            'account_id' => $faker->numberBetween(1,3),
            'kategori_id' => $faker->numberBetween(1,4),
            'tanggal' => $faker->dateTimeBetween($startDate = '-20 days', $endDate = '+8 days', $timezone = null),
            'aktivitas' => $faker->word,
            'jumlah' => $faker->numberBetween(19000,29000000),
            'catatan' => '',
            'created_at' => Carbon::now()->format('Y/m/d H:i:s'),
            'updated_at' => Carbon::now()->format('Y/m/d H:i:s'),
        ]);

        }
    }
}
