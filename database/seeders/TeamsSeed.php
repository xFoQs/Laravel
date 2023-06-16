<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamsSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teams = [

            ['name' => 'Karpaty Krosno', 'league_id'=>'1',],
            ['name' => 'Stal Łańcut','league_id'=>'1',],
            ['name' => 'JKS Jarosław','league_id'=>'1',],
            ['name' => 'Sokół Kolbuszowa Dolna','league_id'=>'1',],

            ['name' => 'Iskra Zgłobień','league_id'=>'2',],
            ['name' => 'Jedność Niechobrz','league_id'=>'2',],
            ['name' => 'Sawa Solina','league_id'=>'2',],
            ['name' => 'Strumyk Malawa','league_id'=>'2',],


            ['name' => 'LKS Jasionka','league_id'=>'3',],
            ['name' => 'Wisłok Strzyżów','league_id'=>'3',],
            ['name' => 'GKS Niebylec','league_id'=>'3',],
            ['name' => 'Heiro Rzeszów','league_id'=>'3',],

            ['name' => 'Trzcianka Trzciana','league_id'=>'4',],
            ['name' => 'Resovia III','league_id'=>'4',],
            ['name' => 'Bratek Bratkowice','league_id'=>'4',],
            ['name' => 'Mrowianka Mrowla','league_id'=>'4',],

        ];

        Team::insert($teams);
    }
}
