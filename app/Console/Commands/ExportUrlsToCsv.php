<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportUrlsToCsv extends Command
{
    protected $signature = 'go:export';

    protected $description = 'Export URLs table to a CSV file';

    public function handle()
    {
        $filename = 'urls_export_'.now()->format('Y-m-d_H-i-s').'.csv';
        $filepath = storage_path("app/public/exports/{$filename}");

        // Ensure the directory exists
        if (! file_exists(dirname($filepath))) {
            mkdir(dirname($filepath), 0755, true);
        }

        // Open the file for writing
        $file = fopen($filepath, 'w');

        // Insert CSV header
        fputcsv($file, ['ID', 'Long URL', 'Base URL', 'UTM Source', 'UTM Medium', 'UTM Campaign', 'Created By', 'Redirect Count', 'Last Redirected At', 'Created At', 'Updated At']);

        // Get data from the database and write each row
        $urls = \App\Models\Url::all();
        foreach ($urls as $url) {
            fputcsv($file, [
                $url->id,
                $url->long_url,
                $url->base_url,
                $url->utm_source,
                $url->utm_medium,
                $url->utm_campaign,
                $url->created_by,
                $url->redirect_count,
                $url->last_redirected_at,
                $url->created_at,
                $url->updated_at,
            ]);
        }

        // Close the file
        fclose($file);

        // Convert storage path to a public URL
        $publicPath = asset("storage/exports/{$filename}");

        $this->info("Export completed. Download URL: {$publicPath}");
    }
}
