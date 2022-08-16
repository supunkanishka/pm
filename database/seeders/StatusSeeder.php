<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $string = 'Pending,
			On Hold,
			In Progress,
			Completed';


        $statuses = explode(',', $string);

		foreach ($statuses as $value) {
			DB::table('statuses')->insert([
	            'name' => trim($value),
	        ]);
		}
    }
}
