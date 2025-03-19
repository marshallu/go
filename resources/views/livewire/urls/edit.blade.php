<?php

use App\Models\Url;
use Livewire\Volt\Component;
use App\Livewire\Forms\UrlForm;
use Illuminate\Support\Facades\Gate;

new class extends Component {
	public Url $url;
    public UrlForm $form;

	public function mount(Url $url)
    {
		if (!Gate::allows('edit-url', $this->url)) {
            abort(403, 'Unauthorized access.');
        }

		$this->url = $url;

        $this->form->setUrl($url);
    }

    public function update() {
        $this->form->update();

        Flux::toast(
            heading: 'Changes saved.',
            text: 'The changes to this URL have been saved.',
        );
    }
}; ?>

<div class="max-w-2xl px-6 mx-auto">
	<flux:breadcrumbs>
		<flux:breadcrumbs.item href="/">Home</flux:breadcrumbs.item>
		<flux:breadcrumbs.item>Edit</flux:breadcrumbs.item>
	</flux:breadcrumbs>

	<div class="mt-8">
		<flux:heading size="lg" :accent=true>Edit Your Short URL</flux:heading>
	</div>

	<div class="mt-8">
		<flux:input label="Current Shortened URL" value="{{ env('APP_URL') }}/{{ $url->id }}" readonly copyable />
	</div>

    <form wire:submit.prevent="update" class="space-y-8">
		<div class="mt-8">
			<flux:field>
				<flux:label>Current full url</flux:label>

				<flux:link href="{{ $form->long_url }}" target="_blank">{{ $form->long_url }}</flux:link>
			</flux:field>
		</div>

		<div>
			<flux:field>
				<flux:label>The current QR Code</flux:label>

				<img src="{{ asset('storage/qr_codes/' . $url->id . '.svg') }}" class="h-40 w-40" />

				<flux:description>Right click and save the QR code to use in your materials.</flux:description>
			</flux:field>
		</div>

		<flux:separator />

		<div>
			<flux:field>
				<flux:label>Enter the url that you want to shorten</flux:label>

				<flux:input wire:model="form.base_url" placeholder="https://www.marshall.edu" required />

				@error('form.base_url') <flux:error name="form.base_url" /> @enderror

				<flux:description>This will be publicly displayed.</flux:description>
			</flux:field>
		</div>

		<flux:separator />

		<div>
			<flux:heading size="lg">Google Campaign URL Data</flux:heading>
			<flux:subheading>These fields are optional and are used to track the effectiveness of the shortened URL in Google Analytics.</flux:subheading>
		</div>

		<div>
			<flux:field>
				<flux:label>Campaign Name</flux:label>

				<flux:input wire:model="form.utm_campaign" />

				@error('form.utm_campaign') <flux:error name="form.utm_campaign" /> @enderror

				<flux:description>The Campaign Name should be a short, but descriptive name for the reason for communication. If you plan to have multiple communications on separate days, you can include the date in MMDDYY format at the end. You should use underscores in place of spaces in this value.</flux:description>
			</flux:field>
		</div>

		<div>
			<flux:field>
				<flux:label>Campaign Source</flux:label>

				<flux:input wire:model="form.utm_source" />

				@error('form.utm_source') <flux:error name="form.utm_source" /> @enderror

				<flux:description>The Campaign Source value should be either 'salesforce' or the name of the social media service.</flux:description>
			</flux:field>
		</div>

		<div>
			<flux:field>
				<flux:label>Campaign Medium</flux:label>

				<flux:input wire:model="form.utm_medium" />

				@error('form.utm_medium') <flux:error name="form.utm_medium" /> @enderror

				<flux:description>The Campaign Medium should be 'email' or 'text' if coming from Salesforce or the account username if coming from social media.</flux:description>
			</flux:field>
		</div>

		<flux:button variant="primary" type="submit">Update URL</flux:button>
	</form>
</div>
