<?php

namespace App\Livewire;

use App\Livewire\Forms\UrlForm;
use Livewire\Component;
use Flux\Flux;

class CreateUrl extends Component
{
	public UrlForm $form;

	public function mount() {
		$this->form->newUrl();
	}

	public function store() {
		$this->form->store();

		Flux::toast(
            heading: 'Shortened URL created.',
            text: 'The shortened URL has been created.',
        );
	}

    public function render()
    {
        return view('livewire.create-url');
    }
}
