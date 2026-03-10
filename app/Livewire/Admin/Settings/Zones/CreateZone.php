<?php

namespace App\Livewire\Admin\Settings\Zones;

use App\Models\County;
use App\Models\Zone;
use App\Models\ZoneCounty;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateZone extends Component
{

    public $name;

    public $counties = [];

    public $selectedCounties = [];

    public function mount()
    {
        $this->counties = County::all();
    }
    public function save()
    {
        $this->validate([
            'name' => 'required|unique:zones,name',
            'selectedCounties' => 'array',
            'selectedCounties.*' => 'exists:counties,id',
        ]);

        $assignedCounties = ZoneCounty::whereIn('county_id', $this->selectedCounties)
            ->pluck('county_id')
        ->toArray();

        if (!empty($assignedCounties)) {
            $assignedCountyNames = County::whereIn('id', $assignedCounties)
                ->pluck('name')
                ->implode(', ');
            
            throw ValidationException::withMessages([
                'selectedCounties' => "The following counties are already assigned to other zones: {$assignedCountyNames}"
            ]);
        }



        try {
            DB::beginTransaction();
            $zone = Zone::create([
                'name' => $this->name,
            ]);

            foreach ($this->selectedCounties as $countyId) {
                ZoneCounty::create([
                    'zone_id' => $zone->id,
                    'county_id' => $countyId,
                ]);
            }
            DB::commit();
            return redirect()->route('admin.zones.index')->with(['success', 'Zone created successfully']);
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error creating zone: ' . $th->getMessage());
        }
    }
    public function render()
    {
        return view('livewire.admin.settings.zones.create-zone');
    }
}
