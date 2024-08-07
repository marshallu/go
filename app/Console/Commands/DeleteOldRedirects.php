<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;

class DeleteOldRedirects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will delete redirects that have not been used in the last 2 years.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $urls = Url::where('last_redirected_at', '<', now()->subYears(2))->get();

		$urls->each->delete();

		$this->info("Cleared {$urls->count()} old redirects.");
    }
}
