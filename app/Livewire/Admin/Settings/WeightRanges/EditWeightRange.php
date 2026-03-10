<?php

namespace App\Livewire\Admin\Settings\WeightRanges;

use App\Models\WeightRange;
use Livewire\Component;

class EditWeightRange extends Component
{

    public $min_weight;
    public $max_weight;
    public $label;

    public $weightRange;

    public function mount($id)
    {
        $this->weightRange = WeightRange::findOrFail($id);
        $this->min_weight = $this->weightRange->min_weight;
        $this->max_weight = $this->weightRange->max_weight;
        $this->label = $this->weightRange->label;
    }

    public function submit()
    {
        $this->validate([
            'min_weight' => 'required|numeric|min:0',
            'max_weight' => 'required|numeric|gt:min_weight',
            'label' => 'required|string|max:255',
        ]);

        try {
            $this->weightRange->update([
                'min_weight' => $this->min_weight,
                'max_weight' => $this->max_weight,
                'label' => $this->label,
                'unit' => 'kg',
            ]);

            return redirect()->route('admin.weight-ranges.index')->with('success', 'Weight Range updated successfully');
        } catch (\Throwable $th) {
            session()->flash('error', 'Failed to update weight range.' . $th->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.weight-ranges.edit-weight-range');
    }
}
