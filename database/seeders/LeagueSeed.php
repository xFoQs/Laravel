<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\League;

class LeagueSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $leagues = [

            ['name' => 'IV liga podkarpacka',],
            ['name' => 'Klasa okręgowa podkarpacka',],
            ['name' => 'A klasa podkarpacka',],
            ['name' => 'B klasa podkarpacka',],

        ];

        League::insert($leagues);
    }
}
