<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        /*foreach (range(1, 5) as $index)
        {
            User::create([
                'username' => $faker->word . $index,
                'email' => $faker->email,
                'password' => "123"
                //'parent_id' => $faker->randomElement([1,2,3,4])

            ]);
        }*/
        User::create([
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => "123"
        ]);

    }

}