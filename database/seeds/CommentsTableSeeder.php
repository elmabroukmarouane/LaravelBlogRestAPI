<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $posts_id_array = array();
        for($count = 1; $count <= 50; $count++) {
            array_push($posts_id_array, $count);
        }
        $count_random = 49;
        foreach(range(2,11) as $i)
        {
            foreach(range(1,5) as $index)
            {
                $random_post_id_index = rand(0, $count_random);
                $post = Comment::create([
                    'user_id' => $i,
                    'post_id' => $posts_id_array[$random_post_id_index],
                    'comment' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true)
                ]);
                array_splice($posts_id_array, $random_post_id_index, 1);
                $count_random--;
            }
        }
    }
}
