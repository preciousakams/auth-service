<?php

use Illuminate\Database\Seeder;
use  App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!User::where('email', 'razaqofficial@gmail.com')->exists()) {
            $administrator = new User();
            $administrator->first_name = 'Abdurrazaq';
            $administrator->last_name = 'AbdulAzeez';
            $administrator->liveizy_id = 'ADM129384R';
            $administrator->email = 'razaqofficial@gmail.com';
            $administrator->email_verified_at = now();
            $administrator->password = bcrypt('razaq123');
            $administrator->save();

            $administrator->assignRole('Super Admin');
        }

    }
}
