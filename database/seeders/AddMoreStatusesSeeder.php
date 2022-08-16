<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddMoreStatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = array("Review"=>"#0DAAD8","Bug"=>"#FFA500");

        foreach ($statuses as $key => $value) {
        	DB::table('statuses')->insert([
	            'name' => trim($key),
	            'color' => trim($value),
	        ]);
        }
    }
}
