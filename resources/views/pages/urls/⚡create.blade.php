<?php

use App\Livewire\Forms\UrlForm;
use Livewire\Attributes\Title;
use Livewire\Component;

new
#[Title('Create Shortened URL')]
class extends Component
{
    public UrlForm $form;

	public function mount()
    {
        $this->form->newUrl();
    }

    public function store()
    {
        $this->form->store();

        Flux::toast(
            heading: 'Shortened URL created.',
            text: 'The shortened URL has been created.',
        );
    }
};
?>

<div class="max-w-2xl px-6 mx-auto">
	@if($errors->any())
		<flux:callout variant="danger">
			<flux:callout.heading icon="x-circle">Something went wrong.</flux:callout.heading>

			<flux:callout.text>
				<ul>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</flux:callout.text>
		</flux:callout>
	@endif

	<flux:callout variant="secondary">
		<flux:callout.heading icon="information-circle">Note</flux:callout.heading>

		<flux:callout.text>
			You can shorten any marshall.edu, jcesom.marshall.edu, formarshallu.org, marshallhealth.org or Dynamic Forms URL.
		</flux:callout.text>
	</flux:callout>

    <form wire:submit.prevent="store" class="space-y-8 mt-8">
		<div>
			<flux:field>
				<flux:label>Enter your url</flux:label>

				<flux:input wire:model="form.base_url" placeholder="https://www.marshall.edu" required />

				@error('form.base_url') <flux:error name="form.base_url" /> @enderror

				<flux:description>This will be publicly displayed.</flux:description>
			</flux:field>
		</div>

		<div>
			<flux:select wire:model="form.linksTo" label="Links to">
				<flux:select.option value="short">Shortened URL</flux:select.option>
				<flux:select.option value="full">The Full URL</flux:select.option>
			</flux:select>

			@error('form.linksTo') <flux:error name="form.linksTo" /> @enderror
		</div>

		<div>
			<flux:select wire:model="form.foreground_color" label="Foreground Color">
				<flux:select.option value="green">Green</flux:select.option>
				<flux:select.option value="black">Black</flux:select.option>
			</flux:select>

			@error('form.linksTo') <flux:error name="form.foreground_color">{{ $messge }}</flux:error> @enderror
		</div>

		<flux:separator />

		<div>
			<flux:heading size="lg">Custom alias</flux:heading>
			<flux:subheading>For example, if you set the Custom Alias to 'admissions' the shortened URL will be https://go.marshall.edu/admissions.</flux:subheading>
		</div>

		<div>
			<flux:field>
				<flux:label>Enter the Custom Alias (Optional)</flux:label>

				<flux:input wire:model="form.customAlias" />

				@error('form.customAlias') <flux:error name="form.customAlias" /> @enderror

				<flux:description>Note: The is an identifier and can not be edited. If you need to update the alias you must delete URL and recreate with new alias.</flux:description>
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

		<flux:button variant="primary" type="submit">Shorten URL</flux:button>
	</form>
</div>
