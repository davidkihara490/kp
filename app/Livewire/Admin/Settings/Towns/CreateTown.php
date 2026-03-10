<?php

namespace App\Livewire\Admin\Settings\Towns;

use App\Models\County;
use App\Models\SubCounty;
use App\Models\Town;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CreateTown extends Component
{
        public $counties = [];
    public $subCounties = [];
    public ?string $name;
    public bool $status = true;
    public ?int $county_id;
    public ?int $sub_county_id;

    public function mount()
    {
        $this->counties = County::all();
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
            Town::create([
                'name' => $this->name,
                'code' => $this->generateTownCode(),
                'county_id' => $this->county_id,
                'sub_county_id' => $this->sub_county_id,
                'status' => $this->status,
            ]);

            return redirect()->route('admin.towns.index')->with('success', 'Town created successfully.');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create town: ' . $e->getMessage());
            return;
        }
    }


    public function render()
    {
        return view('livewire.admin.settings.towns.create-town');
    }

        public function updatedCountyId($value)
    {
        $this->subCounties = SubCounty::where('county_id', $value)->get();
        $this->sub_county_id = null;
    }

    private function generateTownCode(): string
    {
        $subCounty = SubCounty::findOrFail($this->sub_county_id);
        $townCount = Town::where('sub_county_id', $this->sub_county_id)->count();

        return $subCounty->code . ($townCount + 1);
    }
}
