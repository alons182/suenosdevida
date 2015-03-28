<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Profile;


class ProfilesTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker::create();

        //foreach (range(1, 6) as $index)
       // {
            Profile::create([
                'user_id' => 1,
                'first_name'=> $faker->word,
                'last_name'=> $faker->word,
                'ide'=> $faker->randomNumber(),
                'address'=> $faker->address,
                'code_zip'=> $faker->postcode,
                'telephone'=> $faker->phoneNumber,
                'country'=> 'Costa Rica',
                'province'=> 'Guanacaste',
                'canton'=> 'Liberia',
                'city'=> $faker->city,
                'bank'=> $faker->word,
                'type_account'=> $faker->creditCardType,
                'number_account'=> $faker->creditCardNumber,
                'skype'=> $faker->word
            ]);
       // }



    }

}