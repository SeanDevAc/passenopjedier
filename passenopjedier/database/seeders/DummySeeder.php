<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('human')->insert([
            'name' => Str::random(10),
            'email' => Str::random(10).'@email.com',
            'password' => Str::random(10),
            'haspet' => false,
        ]);
    }
}
