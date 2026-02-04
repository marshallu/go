<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';

        DB::table('urls')->insert([
            'id' => 'history',
            'base_url' => 'https://www.marshall.edu/history/',
            'long_url' => 'https://www.marshall.edu/history/',
            'created_by' => 'cmccomas',
            'redirect_count' => 0,
            'last_redirected_at' => null,
        ]);

        DB::table('urls')->insert([
            'id' => 'cyber',
            'long_url' => 'http://www.marshall.edu/cyber/?utm_source=ncc&utm_medium=leaderboard&utm_campaign=2425',
            'base_url' => 'http://www.marshall.edu/cyber/',
            'utm_source' => 'ncc',
            'utm_medium' => 'leaderboard',
            'utm_campaign' => '2425',
            'created_by' => 'bajus',
            'redirect_count' => 0,
            'last_redirected_at' => null,
        ]);
    }
}
