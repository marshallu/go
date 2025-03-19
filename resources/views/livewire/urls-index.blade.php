<div class="container mx-auto">
	<form class="grid lg:grid-cols-2 gap-12">
		<flux:input wire:model.live="search" label="Search for a URL" placeholder="Search for a URL" />

		<flux:select wire:model.live="userSearch" label="Search by user" placeholder="Users...">
			<flux:select.option value="">All Users</flux:select.option>
			@foreach($users as $user)
				<flux:select.option value="{{ $user->id }}">{{ $user->name }}</flux:select.option>
			@endforeach
		</flux:select>
	</form>

	<flux:table :paginate="$urls">
		<flux:table.columns>
			<flux:table.column>Short URL</flux:table.column>
			<flux:table.column>Full URL</flux:table.column>
			<flux:table.column>Created By</flux:table.column>
			<flux:table.column>Created Date</flux:table.column>
			<flux:table.column>Last Used</flux:table.column>
			<flux:table.column>Edit</flux:table.column>
		</flux:table.columns>
		@forelse($urls as $url)
			<flux:table.row>
				<flux:table.cell>{{ $url->id }}</flux:table.cell>
				<flux:table.cell class="whitespace-normal!">{{ $url->long_url }}</flux:table.cell>
				<flux:table.cell>{{ $url->user->email ?? '' }}</flux:table.cell>
				<flux:table.cell>{{ Carbon\Carbon::create($url->created_at)->format('M d, Y') }}</flux:table.cell>
				<flux:table.cell>{{ Carbon\Carbon::create($url->last_redirected_at)->diffForHumans() }}</flux:table.cell>
				<flux:table.cell>
					<flux:link href="{{ route('url.edit', $url) }}" class="text-green underline hover:no-underline">Edit</flux:link>
				</flux:table.cell>
			</flux:table.row>
		@empty
			<flux:table.row>
				<flux:table.cell colspan="6">No URLs found.</flux:table.cell>
			</flux:table.row>
		@endforelse
	</flux:table>
</div>
