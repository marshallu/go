<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\Url;
use App\Rules\IsAllowedDomain;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Storage;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\SvgWriter;

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
		];
	}

	public function newUrl()
	{
		$this->url = new Url();
	}

	public function setUrl(Url $url)
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

	private function getUrl()
	{
		$long_url = $this->base_url;

		if ($this->utm_source) {
			$long_url .= '?utm_source=' . $this->utm_source;
		}

		if ($this->utm_medium) {
			$long_url .= '&utm_medium=' . $this->utm_medium;
		}

		if ($this->utm_campaign) {
			$long_url .= '&utm_campaign=' . $this->utm_campaign;
		}

		return $long_url;
	}

	private function getId()
	{
		if ($this->id) {
			return $this->id;
		}

		$possibleCharacters = 'abcdefghijkmnopqrstuvwxyz234567890';
		return substr(str_shuffle($possibleCharacters), 0, 6);
	}

	public function store()
	{
		$this->validate();

		$url = new Url();
		$url->id = $this->getId();
		$url->long_url = $this->getUrl();
		$url->base_url = $this->base_url;
		$url->utm_source = $this->utm_source;
		$url->utm_medium = $this->utm_medium;
		$url->utm_campaign = $this->utm_campaign;
		$url->last_redirected_at = now();
		$url->save();

		$this->createQrCode(env('APP_URL') . '/' . $url->id, $url->id);

		return redirect()->route('url.edit', $url);
	}

	public function update()
	{
		$this->validate();

		$this->url->long_url = $this->getUrl();
		$this->url->base_url = $this->base_url;
		$this->url->utm_source = $this->utm_source;
		$this->url->utm_medium = $this->utm_medium;
		$this->url->utm_campaign = $this->utm_campaign;
		$this->url->last_redirected_at = now();
		$this->url->save();

		if($this->oldLongUrl != $this->url->long_url) {
			$this->createQrCode($this->url->long_url, $this->url->id);
		}

		return redirect()->route('url.edit', $this->url);
	}

	public function createQrCode($url, $id)
	{
		// Check if QR code exists, if so remove it.
		$current_qr_code_file = storage_path('/public/qr_codes/' . $id . '.svg');

		if (file_exists($current_qr_code_file)) {
			unlink($current_qr_code_file);
		}

		$writer = new SvgWriter();

		// Create QR code
		$qrCode = QrCode::create($url)
			->setEncoding(new Encoding('ISO-8859-1'))
			->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
			->setSize(1080)
			->setMargin(36)
			->setRoundBlockSizeMode(RoundBlockSizeMode::Margin)
			->setForegroundColor(new Color(0, 177, 64))
			->setBackgroundColor(new Color(255, 255, 255));


			// Create generic logo
			$qrLogo = Logo::create(storage_path('icons/m_primary.svg'))
				->setResizeToWidth(500)
				->setResizeToHeight(500);

			// Get the string version of the SVG QR code
			$result = $writer->write($qrCode, $qrLogo)->getString();

			// Get the SVG icon and prep it for preg_replace with back reference
			$svg_file = file_get_contents(storage_path('icons/m_primary.svg'));
			$svg_file = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $svg_file);
			$svg_file = str_replace('<svg', '<svg x="$1" y="$2" width="$3" height="$4"', $svg_file);

			// Replace image with SVG icon, use back reference to get the variables we need
			$result = preg_replace(
				'/<image x="([\d]+)" y="([\d]+)" width="([\d]+)" height="([\d]+)".*\/>/',
				$svg_file,
				$result
			);

			// Write the files to the cache
			Storage::put('/public/qr_codes/' . $id . '.svg', $result, 'public');
	}
}
