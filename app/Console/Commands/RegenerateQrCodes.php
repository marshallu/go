<?php

namespace App\Console\Commands;

use App\Livewire\Forms\UrlForm;
use App\Models\Url;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RegenerateQrCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'go:regenerate-qr-codes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a green QR code pointing at the short URL for every URL whose QR code file is missing';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = Storage::disk('public');
        $regeneratedCount = 0;

        Url::query()->select('id')->lazyById()->each(function (Url $url) use ($disk, &$regeneratedCount): void {
            if ($disk->exists('qr_codes/'.$url->id.'.svg')) {
                return;
            }

            UrlForm::createQrCode(rtrim(config('app.url'), '/').'/'.$url->id, $url->id, 'green');
            $regeneratedCount++;
        });

        $this->info("Total QR codes regenerated: $regeneratedCount");

        return self::SUCCESS;
    }
}
