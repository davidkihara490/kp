<?php

namespace App\Livewire\Admin\Settings\Towns;

use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use Livewire\Component;

class EditTown extends Component
{
    public $counties = [];
    public $subCounties = [];
    public ?string $name;
    public bool $status = true;
    public ?int $county_id;
    public ?int $sub_county_id;
    public $town;

    public function mount($id)
    {
        $this->counties = County::all();
        $this->town = Town::findOrFail($id);

        $this->name = $this->town->name;
        $this->status = $this->town->status;
        $this->county_id = $this->town->subCounty->county_id;
        $this->sub_county_id = $this->town->sub_county_id;
    }

    public function submit()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'county_id' => 'required|exists:counties,id',
            'sub_county_id' => 'required|exists:sub_counties,id',
            'status' => 'boolean',
        ]);

        try {
            $this->town->update([
                'name' => $this->name,
                'county_id' => $this->county_id,
                'sub_county_id' => $this->sub_county_id,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.towns.index')->with('success', 'Town updated successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update town: ' . $e->getMessage());
            return;
        }
    }
    public function render()
    {
        return view('livewire.admin.settings.towns.edit-town');
    }

    public function updatedCountyId($value)
    {
        $this->subCounties = SubCounty::where('county_id', $value)->get();
        $this->sub_county_id = null;
    }
}
