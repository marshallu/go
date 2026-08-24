<?php

namespace App\Console\Commands;

use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\select;

class ImportUrlsFromCsv extends Command
{
    protected $signature = 'go:import {--file=} {--replace : Delete existing URLs before importing}';

    protected $description = 'Import URLs from a CSV file';

    public function handle(): int
    {
        $confirmed = $this->option('replace') ?: confirm('Do you want to delete existing URLS?');

        if ($confirmed) {
            Url::truncate();
        }

        // Get the list of CSV files in storage
        $files = collect(Storage::disk('public')->files('exports'))
            ->filter(fn ($file) => str_ends_with($file, '.csv'))
            ->map(fn ($file) => basename($file))
            ->values()
            ->toArray();

        // Ensure there are files available
        if (empty($files)) {
            $this->error('No CSV files found in storage/exports/');

            return self::FAILURE;
        }

        $filename = $this->option('file') ?: select(label: 'Select a CSV file to import', options: $files);

        if (! in_array($filename, $files, true)) {
            $this->error("File not found: {$filename}");

            return self::FAILURE;
        }

        $filepath = Storage::disk('public')->path('exports/'.$filename);

        if (! file_exists($filepath)) {
            $this->error("File not found: {$filepath}");

            return self::FAILURE;
        }

        // Open CSV file and process it
        $file = fopen($filepath, 'r');
        $headers = fgetcsv($file);

        if ($headers === false) {
            fclose($file);
            $this->error('The CSV file is empty.');

            return self::FAILURE;
        }

        while (($row = fgetcsv($file)) !== false) {
            $data = array_combine($headers, $row);

            Url::updateOrCreate(
                ['id' => $data['ID']],
                [
                    'long_url' => trim($data['Long URL']),
                    'base_url' => trim($data['Base URL']),
                    'utm_source' => $data['UTM Source'] === '' ? null : trim($data['UTM Source']),
                    'utm_medium' => $data['UTM Medium'] === '' ? null : trim($data['UTM Medium']),
                    'utm_campaign' => $data['UTM Campaign'] === '' ? null : trim($data['UTM Campaign']),
                    'created_by' => $data['Created By'] === '' ? null : trim($data['Created By']),
                    'redirect_count' => is_numeric($data['Redirect Count']) ? $data['Redirect Count'] : 0,
                    'last_redirected_at' => ! empty($data['Last Redirected At']) ? $data['Last Redirected At'] : null,
                    'created_at' => ! empty($data['Created At']) ? $data['Created At'] : now(),
                    'updated_at' => now(),
                ]
            );
        }

        fclose($file);
        $this->info('Import successful!');

        return self::SUCCESS;
    }
}
