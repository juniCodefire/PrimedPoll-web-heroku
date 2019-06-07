<?php

use App\User;
use App\Poll;
use App\Intrest;
use Carbon\Carbon;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Admin::create([
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        $faker = Faker::create();
            $email = [ 'chizaram@example.com', 'frebby@example.com', 'emmanuel@example.com', 'favour@example.com', 'jennifer@example.com', 'sammy@example.com', 'chibuzor@example.com', 'juni@example.com', 'jerry@example.com', 'francis@example.com'];
                foreach ( $email as $email ) { 
                    \App\User::create([
                        'first_name' => $faker->firstName,
                        'last_name' => $faker->lastName,
                        'email' => trim( strtolower( $email ) ),
                        'dob' => "1993-05-15",
                        'phone' => "+2347837867762",
                        'image' => 'https://res.cloudinary.com/iro/image/upload/v1552487696/Backtick/noimage.png',
                        'email_verified_at' => new Datetime(),
                        'password' => Hash::make('password'),
                    ]);
                }

            $faker = Faker::create();
            $interest = [ 'football', 'politics', 'movie', 'tech', 'research', 'sex', 'relationship', 'money'];
            foreach ( $interest as $interest ) { 
                \App\Interest::create([
                    'title' => trim( strtolower( $interest ) ),
                ]);
             }


            //   $faker = Faker::create();
            //   $interest_id = DB::table('interests')->pluck('id');
            //   $owner_id = DB::table('users')->pluck('id');
            //    foreach (range(1,20) as $index){
            //         \App\Userinterest::create([
            //             'owner_id' => $faker->randomElement($owner_id),
            //             'interest_id' => $faker->randomElement($interest_id)
            //         ]);
            //     }

            //  $faker = Faker::create();
            //   $interest_id = DB::table('interests')->pluck('id');
            //   $owner_id = DB::table('users')->pluck('id');
            //    foreach (range(1,50) as $index){
            //         \App\Poll::create([
            //             'question' => $faker->sentence,
            //             'owner_id' => $faker->randomElement($owner_id),
            //             'interest_id' => $faker->randomElement($interest_id)
            //         ]);
            //    }


            //  $faker = Faker::create();
            //   $owner_id = DB::table('users')->pluck('id');
            //   $polled_id = DB::table('polls')->pluck('id');
            //    foreach (range(1,50) as $index){
            //         \App\Option::create([
            //             'option' => $faker->sentence,
            //             'poll_id' => $faker->randomElement($polled_id),
            //             'owner_id' => $faker->randomElement($owner_id)
            //         ]);
            //    }

        }



}
