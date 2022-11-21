<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //factory(\App\Models\User::class, 5)->create();
        $this->call(PermissionSeeder::class);
        $this->call(UserTableSeeder::class);
        //$this->call(AdministratorTableSeeder::class);
    }
}
