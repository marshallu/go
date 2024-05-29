<?php

namespace App\Livewire;

use App\Models\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;

class UrlsIndex extends Component
{
	use WithPagination, WithoutUrlPagination;

	public $search = '';
	public $userSearch = false;

	public function updatedSearch()
    {
        $this->resetPage();
    }

	public function updatedUserSearch()
	{
		$this->resetPage();
	}

	public function deleteUrl($id)
	{
		Url::find($id)->delete();

	}

    public function render()
    {
		$urls = Url::query();

		if ($this->search && !$this->userSearch) {
			$urls->where('long_url', 'like', '%' . $this->search . '%');
		}

		if (!$this->search && $this->userSearch) {
			$urls->where('created_by', $this->userSearch);
		}

		if ($this->search && $this->userSearch) {
			$urls->where('created_by', $this->userSearch)->where('long_url', 'like', '%' . $this->search . '%');
		}

        return view('livewire.urls-index', [
			'urls' => $urls->orderby('created_at', 'desc')->paginate(5),
			'users' => Url::distinct()->pluck('created_by'),
		]);
    }
}
