<?php

namespace App\Livewire\Procedures;

use App\Models\Procedure;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Update extends Component
{
    public Procedure $procedure;

    #[Validate('required|string|max:255|unique:procedures,code,{id}')]
    public string $code = '';

    #[Validate('required|string|max:255|unique:procedures,name,{id}')]
    public string $name = '';

    #[Validate('nullable|string')]
    public string $description = '';

    #[Validate('required|numeric|min:0')]
    public string $default_price = '';

    #[Validate('required|string|in:consultation,filling,extraction,crown,cleaning,root_canal,implant,whitening,other')]
    public string $category = '';

    #[Validate('boolean')]
    public bool $is_active = true;

    public function mount()
    {
        $this->code = $this->procedure->code;
        $this->name = $this->procedure->name;
        $this->description = $this->procedure->description ?? '';
        $this->default_price = (string)$this->procedure->default_price;
        $this->category = $this->procedure->category;
        $this->is_active = $this->procedure->is_active;
    }

    public function update()
    {
        $this->validate([
            'code' => "required|string|max:255|unique:procedures,code,{$this->procedure->id}",
            'name' => "required|string|max:255|unique:procedures,name,{$this->procedure->id}",
            'description' => 'nullable|string',
            'default_price' => 'required|numeric|min:0',
            'category' => 'required|string|in:consultation,filling,extraction,crown,cleaning,root_canal,implant,whitening,other',
            'is_active' => 'boolean',
        ]);

        $this->procedure->update($this->only('code', 'name', 'description', 'default_price', 'category', 'is_active'));

        $this->dispatch('procedureUpdated');
    }

    public function render()
    {
        $categories = ['consultation', 'filling', 'extraction', 'crown', 'cleaning', 'root_canal', 'implant', 'whitening', 'other'];

        return view('livewire.procedures.update', [
            'categories' => $categories,
        ]);
    }
}
