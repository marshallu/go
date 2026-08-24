<?php

namespace App\Livewire\Forms;

use App\Models\Url;
use App\Rules\IsAllowedDomain;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Form;

class UrlForm extends Form
{
    public ?Url $url;

    public $id;

    public $long_url;

    public $oldLongUrl;

    public $base_url;

    public $utm_source;

    public $utm_medium;

    public $utm_campaign;

    public $user;

    public $foreground_color = 'green';

    public $linksTo = 'short';

    #[Validate('nullable|max:24|unique:urls,id')]
    public $customAlias;

    public function rules()
    {
        return [
            'base_url' => ['required', 'url', 'max:2000', new IsAllowedDomain],
            'utm_source' => 'nullable|alpha_num|max:50',
            'utm_medium' => 'nullable|alpha_num|max:50',
            'utm_campaign' => 'nullable|alpha_num|max:50',
            'customAlias' => 'nullable|max:24|unique:urls,id',
            'foreground_color' => 'nullable',
        ];
    }

    public function newUrl(): void
    {
        $this->url = new Url;
        $this->user = auth()->user();
    }

    public function setUrl(Url $url): void
    {
        $this->url = $url;
        $this->id = $url->id;
        $this->base_url = $url->base_url;
        $this->long_url = $url->long_url;
        $this->utm_source = $url->utm_source;
        $this->utm_medium = $url->utm_medium;
        $this->utm_campaign = $url->utm_campaign;
        $this->oldLongUrl = $url->long_url;
    }

    private function getUrl(): string
    {
        $long_url = $this->base_url;
        $separator = (str_contains($long_url, '?')) ? '&' : '?';

        $queryParams = [];

        if ($this->utm_source) {
            $queryParams[] = 'utm_source='.$this->utm_source;
        }

        if ($this->utm_medium) {
            $queryParams[] = 'utm_medium='.$this->utm_medium;
        }

        if ($this->utm_campaign) {
            $queryParams[] = 'utm_campaign='.$this->utm_campaign;
        }

        if (! empty($queryParams)) {
            $long_url .= $separator.implode('&', $queryParams);
        }

        return $long_url;
    }

    private function getId(): string
    {
        if ($this->id) {
            return $this->id;
        }

        $possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';

        return substr(str_shuffle($possibleCharacters), 0, 6);
    }

    public function store(): mixed
    {
        $this->validate();

        $url = new Url;

        if ($this->customAlias) {
            $url->id = $this->customAlias;
        } else {
            $url->id = $this->getId();
        }

        $url->long_url = $this->getUrl();
        $url->base_url = $this->base_url;
        $url->utm_source = $this->utm_source;
        $url->utm_medium = $this->utm_medium;
        $url->utm_campaign = $this->utm_campaign;
        $url->user_id = auth()->user()->id;
        $url->last_redirected_at = now();
        $url->save();

        if ($this->linksTo == 'short') {
            $this->createQrCode(
                rtrim(config('app.url'), '/').'/'.$url->id,
                $url->id,
                $this->foreground_color
            );
        } else {
            $this->createQrCode($url->long_url, $url->id, $this->foreground_color);
        }

        return redirect()->route('urls.edit', $url);
    }

    public function update(): void
    {
        $this->validate();

        $this->url->long_url = $this->getUrl();
        $this->url->base_url = $this->base_url;
        $this->url->utm_source = $this->utm_source;
        $this->url->utm_medium = $this->utm_medium;
        $this->url->utm_campaign = $this->utm_campaign;
        $this->url->last_redirected_at = now();
        $this->url->save();

        if ($this->oldLongUrl != $this->url->long_url) {
            $this->createQrCode($this->url->long_url, $this->url->id, $this->foreground_color);
        }
    }

    public function createQrCode(string $url, string $id, string $foregroundColor): void
    {
        $disk = Storage::disk('public');
        $path = 'qr_codes/'.$id.'.svg';

        $disk->delete($path);

        $writer = new SvgWriter;

        if ($foregroundColor === 'green') {
            $foregroundColor = new Color(0, 177, 64);
        } else {
            $foregroundColor = new Color(0, 0, 0);
        }

        $qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('ISO-8859-1'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize(1080)
            ->setMargin(36)
            ->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
            ->setForegroundColor($foregroundColor)
            ->setBackgroundColor(new Color(255, 255, 255));

        $qrLogo = Logo::create(storage_path('icons/m_primary.svg'))
            ->setResizeToWidth(500)
            ->setResizeToHeight(500);

        $result = $writer->write($qrCode, $qrLogo)->getString();

        $svg_file = file_get_contents(storage_path('icons/m_primary.svg'));
        $svg_file = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $svg_file);
        $svg_file = str_replace('<svg', '<svg x="$1" y="$2" width="$3" height="$4"', $svg_file);

        $result = preg_replace(
            '/<image x="([\d]+)" y="([\d]+)" width="([\d]+)" height="([\d]+)".*\/>/',
            $svg_file,
            $result
        );

        $disk->put($path, $result);
    }
}
