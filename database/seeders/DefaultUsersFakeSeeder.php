<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DefaultUsersFakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Crear 1000 usuarios aleatorios
        $users = [];
        for ($i = 0; $i < 1000; $i++) {
            $users[] = [
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }


        User::insert($users);
    }


}
