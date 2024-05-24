<?php

namespace App\Livewire;

use App\Models\Url;
use Livewire\Component;
use App\Livewire\Forms\UrlForm;

class EditUrl extends Component
{
	public Url $url;
	public UrlForm $form;

	public $showSuccessIndicator = false;

	public function mount(Url $url) {
		$this->form->setUrl($url);
	}

	public function update() {
		$this->form->update();
		$this->showSuccessIndicator = true;
	}

	public function render()
    {
        return view('livewire.edit-url');
    }
}
