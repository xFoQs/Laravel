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

            ['name' => 'GKS Bełchatów',],
            ['name' => 'KS Kutno',],
            ['name' => 'AKS SMS Łódz',],
            ['name' => 'Sokół Aleksandrów Łódzki',],

        ];

        Team::insert($teams);
    }
}
