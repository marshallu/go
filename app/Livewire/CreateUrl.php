<?php

namespace App\Livewire;

use App\Livewire\Forms\UrlForm;
use Livewire\Component;

class CreateUrl extends Component
{
	public UrlForm $form;

	public function mount() {
		$this->form->newUrl();
	}

	public function store() {
		$this->form->store();
	}

    public function render()
    {
        return view('livewire.create-url');
    }
}
