<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        User::create([
            'name' => $faker->name,
            'birthdate' => $faker->date('Y-m-d', '1999-01-01'),
            'email' => $faker->email,
            'password' => bcrypt('123456'),
            'user_role' => 'super_admin'
        ]);
        foreach(range(1,10) as $index)
        {
            $user = User::create([
                'name' => $faker->name,
                'birthdate' => $faker->date('Y-m-d', '1999-01-01'),
                'email' => $faker->email,
                'password' => bcrypt('123456'),
                'user_role' => 'user'
            ]);
        }
    }
}
