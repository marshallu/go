<div class="max-w-2xl px-6 mx-auto">
	<div
		x-cloak
		x-show="$wire.showSuccessIndicator"
		x-transition.out.opacity.duration.2000ms
		x-effect="if($wire.showSuccessIndicator) setTimeout(() => $wire.showSuccessIndicator = false, 3000)"
		class="bg-green-dark border border-green-darkest text-white rounded px-4 py-4 mb-8"
	>
		SUCCESS! This shortened URL has been successfully updated.
	</div>

	<div class="my-8">
		<h1 class="font-bold text-xl">Edit Your Short URL</h1>
	</div>

	<div class="flex flex-col gap-8 mt-8 pt-8"
		x-data="{
			showMessage: false,
			copy() {
				navigator.clipboard.writeText('{{ env('APP_URL') }}/{{ $url->id }}');
				this.showMessage = true;
				setTimeout(() => this.showMessage = false, 1000);
			}
		}"
	>
		<h2 class="font-semibold text-lg">Current Shortened URL</h2>

		<div x-on:click="copy()" class="flex cursor-pointer w-full">
			<div class="py-2 px-4 border rounded-l border-gray-200 border-r-0 flex-1">
				{{ env('APP_URL') }}/{{ $url->id }}
			</div>
			<div class="py-2 px-4 rounded-r border border-gray-200 bg-gray-100 uppercase font-semibold text-gray-600">
				Copy URL
			</div>
		</div>

		<div x-show="showMessage" class="text-sm text-green-darker -mt-2">
			URL Copied to Clipboard
		</div>
	</div>

    <form class="accent-green" wire:submit.prevent="update">
		<div class="mt-8 pt-8">
			<x-forms.label for="form.long_url">The full URL of the current redirect</x-forms.label>
			<a class="text-brown underline hover:text-brown-dark hover:no-underline" target="_blank" href="{{ $form->long_url }}">{{ $form->long_url }}</a>
		</div>

		<div class="mt-8 pt-8">
			<x-forms.label>The current QR Code</x-forms.label>
			<img src="{{ asset('storage/qr_codes/' . $url->id . '.svg') }}" class="h-40 w-40" />
			<span class="mt-2 text-sm">Right click and save the QR code to use in your materials.</span>
		</div>

		<div class="mt-8 pt-8">
			<x-forms.label for="form.base_url">Enter the url that you want to shorten</x-forms.label>
			<x-forms.text-input type="url" wire:model="form.base_url" id="form.base_url" class="w-full" placeholder="https://www.marshall.edu" required />
			@error('form.base_url') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
		</div>

		<div class="border-t border-gray-200 flex flex-col gap-8 mt-8 pt-8">
			<h2 class="font-semibold text-lg">Google Campaign URL Data</h2>

			<div>
				<x-forms.label for="form.utm_campaign">Campaign Name</x-forms.label>
				<x-forms.text-input type="text" wire:model="form.utm_campaign" id="form.utm_campaign" class="w-full" placeholder="Campaign Name" />
				<div class="mt-2 text-sm text-gray-600">The Campaign Name should be a short, but descriptive name for the reason for communication. If you plan to have multiple communications on separate days, you can include the date in MMDDYY format at the end. You should use underscores in place of spaces in this value.</div>
				@error('form.utm_campaign') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
			</div>

			<div>
				<x-forms.label for="form.utm_source">Campaign Source</x-forms.label>
				<x-forms.text-input type="text" wire:model="form.utm_source" id="form.utm_source" class="w-full" placeholder="Campaign Source" />
				<div class="mt-2 text-sm text-gray-600">The Campaign Source value should be either 'salesforce' or the name of the social media service.</div>
				@error('form.utm_source') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
			</div>

			<div>
				<x-forms.label for="form.utm_medium">Campaign Medium</x-forms.label>
				<x-forms.text-input type="text" wire:model="form.utm_medium" id="form.utm_medium" class="w-full" placeholder="Campaign Medium" />
				<div class="mt-2 text-sm text-gray-600">The Campaign Medium should be 'email' or 'text' if coming from Salesforce or the account username if coming from social media.</div>
				@error('form.utm_medium') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
			</div>
		</div>

		<div class="mt-8 pt-8">
			<button type="submit" class="bg-green hover:bg-green-dark text-white font-semibold px-4 py-2 rounded">Update URL</button>
		</div>
	</form>
</div>
