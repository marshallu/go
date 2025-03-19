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

	<flux:callout variant="secondary" icon="information-circle">
		<flux:callout.heading icon="information-circle">Note</flux:callout.heading>

		<flux:callout.text>
			You can shorten any marshall.edu, jcesom.marshall.edu, formarshallu.org, marshallhealth.org or Dynamic Forms URL.
		</flux:callout.text>
	</flux:callout>

    <form wire:submit.prevent="store">
		<div class="space-y-6">
			<div>
				<flux:input wire:model="form.base_url" label="Enter your url" description="This will be publicly displayed." placeholder="https://www.marshall.edu" required />

				@error('form.base_url') <flux:error name="form.base_url" /> @enderror
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
		</div>

		<flux:separator />

		<flux:heading>Custom alias</flux:heading>
		<flux:subheading>For example, if you set the Custom Alias to 'admissions' the shortened URL will be https://go.marshall.edu/admissions.</flux:subheading>

		<div class="space-y-6">
			<div>
				<flux:input wire:model="form.customAlias" label="Enter the Custom Alias (Optional)" description="Note: The is an identifier and can not be edited. If you need to update the alias you must delete URL and recreate with new alias." />

				@error('form.customAlias') <flux:error name="form.customAlias" /> @enderror
			</div>
		</div>

		<flux:separator />

		<flux:heading>Google Campaign URL Data</flux:heading>
		<flux:subheading>These fields are optional and are used to track the effectiveness of the shortened URL in Google Analytics.</flux:subheading>

		<div class="space-y-6">
			<div>
				<flux:input wire:model="form.utm_campaign" label="Campaign Name" description="The Campaign Name should be a short, but descriptive name for the reason for communication. If you plan to have multiple communications on separate days, you can include the date in MMDDYY format at the end. You should use underscores in place of spaces in this value." />

				@error('form.utm_campaign') <flux:error name="form.utm_campaign" /> @enderror
			</div>

			<div>
				<flux:input wire:model="form.utm_source" label="Campaign Source" description="The Campaign Source value should be either 'salesforce' or the name of the social media service." />

				@error('form.utm_source') <flux:error name="form.utm_source" /> @enderror
			</div>

			<div>
				<flux:input wire:model="form.utm_medium" label="Campaign Medium" description="The Campaign Medium should be 'email' or 'text' if coming from Salesforce or the account username if coming from social media." />

				@error('form.utm_medium') <flux:error name="form.utm_medium" /> @enderror
			</div>
		</div>

		<flux:separator />

		<flux:button variant="primary" type="submit">Shorten URL</flux:button>
	</form>
</div>
