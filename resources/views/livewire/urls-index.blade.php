<div class="container mx-auto">
	<form class="grid lg:grid-cols-2 gap-12">

		<input wire:model.live="search" placeholder="Search for a URL" class="text-input w-full px-4 py-2 border border-gray-200 rounded" />

		<select wire:model.live="userSearch" class="form-select w-full px-4 py-2 border border-gray-200 rounded">
			<option value="">All Users</option>
			@foreach($users as $user)
				<option value="{{ $user }}">{{ $user }}</option>
			@endforeach
		</select>
	</form>
	<div class="mt-8 flow-root">
		<div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
			<div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
				<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg">
					<table class="min-w-full divide-y divide-gray-300">
						<thead class="bg-gray-50">
							<tr>
								<th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Short URL</th>
								<th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Full URL</th>
								<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created By</th>
								<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Created Date</th>
								<th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Last Used</th>
								<th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
									<span class="sr-only">Edit</span>
								</th>

							</tr>
						</thead>
						<tbody class="divide-y divide-gray-200 bg-white">
							@forelse($urls as $url)
							<tr>
								<td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $url->id }}</td>
								<td class="px-3 py-4 text-sm text-gray-500">{{ \Illuminate\Support\Str::limit($url->long_url, 100) }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $url->created_by }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Carbon\Carbon::create($url->created_at)->format('M d, Y') }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ Carbon\Carbon::create($url->last_redirected_at)->diffForHumans() }}</td>
								<td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
									<a href="{{ route('url.edit', $url) }}" class="text-green underline hover:no-underline">Edit</a>
								</td>

							</tr>
							@empty
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" colspan="6">No URLs found.</td>
								</tr>
							@endforelse
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="mt-8">
		{{ $urls->links() }}
	</div>
</div>
