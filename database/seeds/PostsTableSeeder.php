<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Post;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach(range(2,11) as $i)
        {
            foreach(range(1,5) as $index)
            {
                $post = Post::create([
                    'user_id' => $i,
                    'title' => $faker->sentence($nbWords = 5, $variableNbWords = true),
                    'content' => $faker->paragraph($nbSentences = 20, $variableNbSentences = true),
                    'image' => $faker->imageUrl($width = 640, $height = 480)
                ]);
            }
        }
    }
}
