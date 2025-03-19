<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Url;

class FixLongUrls extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go:fix-urls';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix URLs that contain multiple "?" in the long_url column of the urls table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $urls = Url::where('long_url', 'LIKE', '%?%?%')->get();
        $fixedCount = 0;

        foreach ($urls as $url) {
            $longUrl = $url->long_url;
            $parts = explode('?', $longUrl, 2); // Split at the first "?"

            if (count($parts) === 2) {
                $fixedUrl = $parts[0] . '?' . str_replace('?', '&', $parts[1]); // Replace only the second "?" with "&"

                $url->long_url = $fixedUrl;
                $url->save();

                $this->info("Fixed URL: $fixedUrl");
                $fixedCount++;
            }
        }

        $this->info("Total URLs fixed: $fixedCount");
    }
}
