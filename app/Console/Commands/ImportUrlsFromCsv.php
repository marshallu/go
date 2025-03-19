<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Url;
use Illuminate\Support\Facades\Storage;
use function Laravel\Prompts\select;
use function Laravel\Prompts\confirm;

class ImportUrlsFromCsv extends Command
{
    protected $signature = 'go:import';
    protected $description = 'Import URLs from a CSV file';

    public function handle()
    {
        $confirmed = confirm('Do you want to delete existing URLS?');

        if ($confirmed) {
            Url::truncate();
        }

        // Get the list of CSV files in storage
        $files = collect(Storage::files('public/exports'))
            ->filter(fn($file) => str_ends_with($file, '.csv'))
            ->map(fn($file) => basename($file))
            ->values()
            ->toArray();

        // Ensure there are files available
        if (empty($files)) {
            $this->error("No CSV files found in storage/exports/");
            return;
        }

        $filename = select(
            label: 'Select a CSV file to import',
            options: $files
        );

        $filepath = storage_path("app/public/exports/{$filename}");

        if (!file_exists($filepath)) {
            $this->error("File not found: {$filepath}");
            return;
        }

        // Open CSV file and process it
        $file = fopen($filepath, 'r');
        $headers = fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($headers, $row);

            Url::updateOrCreate(
                ['id' => $data['ID']],
                [
                    'long_url' => trim($data['Long URL']),
                    'base_url' => trim($data['Base URL']), // Ensure it's not null
                    'utm_source' => trim($data['UTM Source'] ?: null),
                    'utm_medium' => trim($data['UTM Medium'] ?: null),
                    'utm_campaign' => trim($data['UTM Campaign'] ?: null),
                    'created_by' => trim($data['Created By'] ?: null),
                    'redirect_count' => is_numeric($data['Redirect Count']) ? $data['Redirect Count'] : 0,
                    'last_redirected_at' => !empty($data['Last Redirected At']) ? $data['Last Redirected At'] : null,
                    'created_at' => !empty($data['Created At']) ? $data['Created At'] : now(),
                    'updated_at' => now(),
                ]
            );
        }

        fclose($file);
        $this->info("Import successful!");
    }
}
