<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Ad;


class AdsTableSeeder extends Seeder {

    /**
     *
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index)
        {

            $name_ad = $faker->word;
            Ad::create([
                'name'         => $name_ad,
                'slug'         => Str::slug($name_ad),
                'description'  => $faker->text(),
                'province'     => 'Guanacaste',
                'canton'       => 'Liberia',
                'email'        => $faker->email,
                'published'    => 1,
                'featured'     => 0,
                'publish_date' => Carbon::now()

            ]);
        }


    }
}