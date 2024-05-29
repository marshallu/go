<div class="max-w-2xl px-6 mx-auto">
	@if($errors->any())
		<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
			<strong class="font-bold">Whoops! Something went wrong.</strong>
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	@endif

    <form wire:submit.prevent="store">
		<div class="bg-gray-100 border border-gray-200 rounded px-4 py-4">
			You can shorten any marshall.edu, jcesom.marshall.edu, formarshallu.org, or Dynamic Forms URL.
		</div>

		<div class="mt-8 pt-8">
			<x-forms.label for="form.base_url">Enter your url</x-forms.label>
			<x-forms.text-input type="url" wire:model="form.base_url" id="form.base_url" class="w-full" placeholder="https://www.marshall.edu" required />
			@error('form.base_url') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
		</div>

		<div class="border-t border-gray-200 flex flex-col gap-8 mt-8 pt-8">
			<div>
				<h2 class="font-semibold text-lg">Custom Alias</h2>
				<div class="text-sm text-gray-600 font-semibold mt-2">For example, if you set the Custom Alias to 'admissions' the shortened URL will be https://go.marshall.edu/admissions</div>
			</div>

			<div>
				<x-forms.label for="form.customAlias">Enter the Custom Alias (Optional)</x-forms.label>
				<x-forms.text-input type="text" wire:model.blur="form.customAlias" id="form.customAlias" class="w-full" placeholder="mysite" />
				<div class="mt-2 text-sm text-gray-600">Note: The is an identifier and can not be edited. If you need to update the alias you must delete URL and recreate with new alias.</div>
				@error('form.customAlias') <span class="text-red-600 font-semibold mt-2">{{ $message }}</span> @enderror
			</div>
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
			<button type="submit" class="bg-green hover:bg-green-dark text-white font-semibold px-4 py-2 rounded">Shorten URL</button>
		</div>
	</form>
</div>
