<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportUrlsToCsv extends Command
{
    protected $signature = 'go:export';

    protected $description = 'Export URLs table to a CSV file';

    public function handle(): int
    {
        $filename = 'urls_export_'.now()->format('Y-m-d_H-i-s').'.csv';
        $rows = [['ID', 'Long URL', 'Base URL', 'UTM Source', 'UTM Medium', 'UTM Campaign', 'Created By', 'Redirect Count', 'Last Redirected At', 'Created At', 'Updated At']];

        foreach (Url::all() as $url) {
            $rows[] = [
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
            ];
        }

        Storage::disk('public')->put('exports/'.$filename, implode("\n", array_map(function (array $row): string {
            $file = fopen('php://temp', 'r+');
            fputcsv($file, $row);
            rewind($file);

            return rtrim(stream_get_contents($file));
        }, $rows))."\n");

        // Convert storage path to a public URL
        $publicPath = asset("storage/exports/{$filename}");

        $this->info("Export completed. Download URL: {$publicPath}");

        return self::SUCCESS;
    }
}
