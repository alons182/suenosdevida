<?php

// Composer: "fzaninotto/faker": "v1.3.0"
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Category;


class CategoriesTableSeeder extends Seeder {

    /**
     *
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 5) as $index)
        {

            $name_category = $faker->word;
            Category::create([
                'name'        => $name_category,
                'slug'        => Str::slug($name_category),
                'description' => $faker->text(),
                'published'   => 1,
                'featured'    => 0,

            ]);
        }


    }
}