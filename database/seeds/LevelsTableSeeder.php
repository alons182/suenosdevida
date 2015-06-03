<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use App\Level;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Role;

class LevelsTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        Level::create([
            'level' => 1,
            'payment' => 3000,
            'gain' => 3000
        ]);
        Level::create([
            'level' => 2,
            'payment' => 10000,
            'gain' => 10000
        ]);
        Level::create([
            'level' => 3,
            'payment' => 25000,
            'gain' => 25000
        ]);
        Level::create([
            'level' => 4,
            'payment' => 50000,
            'gain' => 50000
        ]);
        Level::create([
            'level' => 5,
            'payment' => 100000,
            'gain' => 100000
        ]);




    }

}