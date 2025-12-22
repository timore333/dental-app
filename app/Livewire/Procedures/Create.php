<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Create extends Component
{
    #[Validate('required|string|max:255|unique:procedures,code')]
    public string $code = '';

    #[Validate('required|string|max:255|unique:procedures,name')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public string $default_price = '';

    #[Validate('required|string|in:consultation,filling,extraction,crown,cleaning,root_canal,implant,whitening,other')]
    public string $category = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    public function save()
    {
        $this->validate();

        Procedure::create($this->only('code', 'name', 'description', 'default_price', 'category', 'is_active'));

        $this->reset();
        $this->dispatch('notify', 'procedureCreated');
    }

    public function render()
    {
        $categories = ['consultation', 'filling', 'extraction', 'crown', 'cleaning', 'root_canal', 'implant', 'whitening', 'other'];

        return view('livewire.procedures.create', [
            'categories' => $categories,
        ]);
    }
}
