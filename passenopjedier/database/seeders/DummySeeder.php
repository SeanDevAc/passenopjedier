<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
Use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // DB::table('users')->insert([
        //     'name' => 'Miclo',
        //     'birthdate' => '3-01-01',
        //     'email' => 'Miclo@gmail.com',
        //     'password' => Hash::make('micloiswit'),
        //     'bio' => 'ik ben miclo en ik ben wit',
        //     'haspet' => true,
        // ]);

        // DB::table('pet')->insert([
        //     'name' => 'Freddy',
        //     'birthdate' => rand(1, 15),
        //     'race' => 'Hamster',
        //     'bio' => Str::random(75),
        //     'ownerid' => 8,
        // ]);

        // DB::table('review')->insert([
        //     'description' => Str::random(75),
        //     'rating' => 3.5,
        //     'placedat' => Carbon::now(),
        //     'ownerid' => 3,
        //     'sitterid' => 1,
        //     'petid' => 1,
        // ]);

        DB::table('vacancy')->insert([
            'rate' => 15.09,
            'datetime' => Carbon::createFromFormat('d/m/Y', '12/02/2025'),
            'duration' => 150,
            'placedat' => Carbon::now(),
            'ownerid' => 8,
            'petid' => 2,
        ]);

        DB::table('vacancy')->insert([
            'rate' => 11.00,
            'datetime' => Carbon::createFromFormat('d/m/Y', '12/02/2025'),
            'duration' => 150,
            'placedat' => Carbon::now(),
            'ownerid' => 8,
            'petid' => 2,
            'sitterid' => 2,
        ]);


    }
}
