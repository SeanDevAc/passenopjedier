<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
Use Carbon\Carbon;

class DummyAuthUsers extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Yorick Broertjes',
            'birthdate' => '1999-08-01',
            'email' => 'broertjes@gmail.com',
            'password' => Hash::make('broertje'),
            'haspet' => false,
        ]);

        DB::table('users')->insert([
            'name' => 'Gijs Berendijk',
            'birthdate' => '2000-09-11',
            'email' => 'gijsberendijk@gmail.com',
            'password' => Hash::make('langeneus'),
            'bio' => 'ik heb een lange neus',
            'haspet' => true,
        ]);
    }
}
