<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
   
    public function run(): void
    {
        $role = DB::table('role')->where('name','admin')->first();

        DB::table('user')->insert([
            'id' => Str::uuid(),
            'name' => 'admin',
            'email' => 'gojosatoru@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'role_id' => $role->id
        ]);
    }
}