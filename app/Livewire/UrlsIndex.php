<?php

namespace App\Livewire;

use App\Models\Url;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithoutUrlPagination;
use Illuminate\Support\Facades\Gate;

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
        if (!Gate::allows('super-admin')) {
            abort(403, 'Unauthorized access.');
        }

        $urls = Url::query();

        if ($this->search && !$this->userSearch) {
            $urls->where('long_url', 'like', '%' . $this->search . '%');
        }

        if (!$this->search && $this->userSearch) {
            $urls->where('user_id', $this->userSearch);
        }

        if ($this->search && $this->userSearch) {
            $urls->where('user_id', $this->userSearch)->where('long_url', 'like', '%' . $this->search . '%');
        }

        return view('livewire.urls-index', [
            'urls' => $urls->orderby('created_at', 'desc')->paginate(25),
            'users' => User::orderBy('email', 'asc')->get(),
        ]);
    }
}
