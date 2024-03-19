<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class DefaultUserSeeder extends Seeder
{
    /**
     * Run the seeder.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->name = 'Jesus Leon';
        $user->email = 'jaleco@gmail.com';
        $user->password = Hash::make('AdminLeon');
        $user->active = true;
        $user->save();
    }


}
