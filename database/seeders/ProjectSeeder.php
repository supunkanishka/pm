<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$string = 'TUTI-V2,
			ISHI,
			SALES TECK,
			Dr. JUGGLER,
			ANALYTIC,
			BIZMO,
			TRIBER,
			SELUMI SAREES,
			K BATIKS,
			BOOKS,
			VISVAMS,
			JOHN KEELS MMS,
			MEET CLIENT APP,
			TELEFONE,
			SIA,
			GIRT,
			ETEN,
			BAURS,
			MIRACLE,
			PRISON,
			KONNECT,
			BIZMO NEWS,
			BUILDING PERMIT SYSTEM,
			RESCONSOLE,
			QASEM,
			PAYLEE,
			PHARMACY,
			DAFF,
			NEW PROJECT,
			SA-JUGGLER,
			URBAN STEMS,
			MONEY TRANSFER,
			BIO TECH,
			UNILEVER,
			SMS,
			APARTMENT MANAGEMENT,
			TALKING TREES,
			GO APP,
			CORAGE DOLLS,
			OTHER';


        $projects = explode(',', $string);

		foreach ($projects as $value) {
			DB::table('projects')->insert([
	            'name' => trim($value),
	        ]);
		}
    }
}
