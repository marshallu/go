<?php

namespace App\Livewire;

use Flux\Flux;
use App\Models\Url;
use Livewire\Component;
use App\Livewire\Forms\UrlForm;
use Illuminate\Support\Facades\Gate;

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

        Flux::toast(
            heading: 'Changes saved.',
            text: 'The changes to this URL have been saved.',
        );
    }

    public function render()
    {
        if (!Gate::allows('edit-url', $this->url)) {
            abort(403, 'Unauthorized access.');
        }

        return view('livewire.edit-url');
    }
}
