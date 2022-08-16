<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $string = 'Annual,
			Casual,
			Sick';


        $types = explode(',', $string);

		foreach ($types as $value) {
			DB::table('types')->insert([
	            'name' => trim($value),
	        ]);
		}
    }
}
