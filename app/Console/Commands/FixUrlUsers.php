<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use App\Models\User;

class FixUrlUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go:fix-url-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This fixes the URLs that have a string user with a FK to the users table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $urls = Url::whereNull('user_id')->get();
        $fixedCount = 0;

        foreach ($urls as $url) {
            // If $url->created_by is empty, skip this URL
            if (empty($url->created_by)) {
                $combinedEmail = 'cnmccomas@marshall.edu';
            } else {
                $combinedEmail = $url->created_by . '@marshall.edu';
            }

            $user = User::firstOrCreate([
                'email' => $combinedEmail,
            ], [
                'name' => $url->created_by ?? 'Unknown',
                'password' => bcrypt(Str::random(24)),
            ]);

            $url->user_id = $user->id;
            // $url->save();
        }
    }
}
