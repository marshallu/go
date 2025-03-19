<?php

use App\Models\Url;
use App\Models\User;
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Gate;



new class extends Component {
	use WithPagination, WithoutUrlPagination;

	public $search = '';
    public $userSearch = false;

	public function updated()
    {
        $this->resetPage();
    }

	public function mount(Url $url)
    {
		if (!Gate::allows('super-admin')) {
            abort(403, 'Unauthorized access.');
        }
    }

	public function deleteUrl($id)
    {
        Url::find($id)->delete();
    }

	public function with(): array
    {
		$urls = Url::query();

		if ($this->search) {
			$urls->where('long_url', 'like', '%' . $this->search . '%');
		}

		if ($this->userSearch) {
			$urls->where('user_id', $this->userSearch);
		}

        return [
            'urls' => $urls->orderby('created_at', 'desc')->paginate(25),
            'users' => User::orderBy('email', 'asc')->get(),
        ];
    }
}; ?>

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

	<flux:table :paginate="$urls" class="mt-8">
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
				<flux:table.cell class="">
					<div class="flex items-center gap-2">
						<span>{{ Str::limit($url->long_url, 50) }}</span>

						<flux:tooltip content="{{ $url->long_url }}">
							<flux:button icon="information-circle" size="sm" variant="ghost" />
						</flux:tooltip>
					</div>
				</flux:table.cell>
				<flux:table.cell>{{ $url->user->email ?? '' }}</flux:table.cell>
				<flux:table.cell>{{ Carbon\Carbon::create($url->created_at)->format('M d, Y') }}</flux:table.cell>
				<flux:table.cell>{{ Carbon\Carbon::create($url->last_redirected_at)->diffForHumans() }}</flux:table.cell>
				<flux:table.cell>
					<flux:button icon="pencil-square" variant="ghost" href="{{ route('urls.edit', $url) }}" />
				</flux:table.cell>
			</flux:table.row>
		@empty
			<flux:table.row>
				<flux:table.cell colspan="6">No URLs found.</flux:table.cell>
			</flux:table.row>
		@endforelse
	</flux:table>
</div>
