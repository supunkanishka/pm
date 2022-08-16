<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$users = [
    		'Anil' => 'anils@insharptechnologies.com',
    		'Devin' => 'deviny@insharptechnologies.com',
    		'Eranda' => 'erandak@insharptechnologies.com',
    		'Ishan' => 'ishans@insharptechnologies.com',
    		'Lakshitha' => 'lakshithaf@bizmo.info',
    		'Manujaya' => 'manujayas@insharptechnologies.com',
    		'Nadeesha' => 'nadeeshas@insharptechnologies.com',
    		'Niroshan' => 'niroshans@insharptechnologies.com',
    		'Pasan' => 'pasanm@insharptechnologies.com',
    		'Ravindra' => 'ravindraw@insharptechnologies.com',
    		'Ravindu' => 'ravinduw@insharptechnologies.com',
    		'Sachith' => 'sachithb@insharptechnologies.com',
    		'Sameesha' => 'sameeshai@insharptechnologies.com',
    		'Udara' => 'udaran@insharptechnologies.com',
    		'Hansi' => 'hansit@bizmo.info',
    		'Dimuthu' => 'dimuthuw@insharptechnologies.com',
    		'Harshanee' => 'harshaneed@insharptechnologies.com',
    		'Ashene' => 'ashenep@insharptechnologies.com',
    		'Chamoda' => 'chamodak@insharptechnologies.com',
    		'Chanaka' => 'chanakaf@insharptechnologies.com',
    		'Yashan' => 'yashanw@insharptechnologies.com',
		];

		DB::table('users')->insert([
            'name' => 'Supun',
            'email' => 'supunr@insharptechnologies.com',
            'password' => Hash::make('Supun@Admin123'),
            'role' => 'Super Admin',
        ]);

		foreach ($users as $key => $value) {
			DB::table('users')->insert([
	            'name' => $key,
	            'email' => $value,
	            'password' => Hash::make('12345678'),
                'role' => 'User',
	        ]);
		}
        
    }
}
