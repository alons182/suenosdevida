<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Role;

class RolesTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        Role::create([
            'name' => 'administrator'
        ]);
        Role::create([
            'name' => 'sub-administrator'
        ]);
        Role::create([
            'name' => 'store'
        ]);
        Role::create([
            'name' => 'member'
        ]);




    }

}