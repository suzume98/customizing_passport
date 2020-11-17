<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
        	'name' => 'Admin' ,
        	'email' => 'admin@admin.com',
        	'password' => \Hash::make('password')
        ]);
    }
}
