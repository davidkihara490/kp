<?php

namespace App\Livewire\Admin\Settings\WeightRanges;

use App\Models\WeightRange;
use Livewire\Component;

class CreateWeightRange extends Component
{

    public $min_weight;
    public $max_weight;
    public $label;
    public $status = 1;

    public function submit()
    {
        $this->validate([
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|gt:min_weight',
            'label' => 'required|string|max:255',
        ]);

        try {
            WeightRange::create([
                'min_weight' => $this->min_weight,
                'max_weight' => $this->max_weight,
                'label' => $this->label,
                'unit' => 'kg',
            ]);

            return redirect()->route('admin.weight-ranges.index')->with('success', 'Weight Range created successfully');
        } catch (\Throwable $th) {
            session()->flash('error', 'Failed to create weight range.' . $th->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.weight-ranges.create-weight-range');
    }
}
