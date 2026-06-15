<?php

namespace App\Livewire\Admin\Settings\Zones;

use App\Models\County;
use App\Models\Town;
use App\Models\Zone;
use App\Models\ZoneCounty;
use App\Models\ZoneTown;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class CreateZone extends Component
{

    public $name;

    public $counties = [];
    public $towns = [];

    public $selectedCounties = [];
    public $selectedTowns = [];


    public function mount()
    {
        $this->counties = County::all();
        $this->towns = Town::all();
    }
    public function save()
    {
        $this->validate([
            'name' => 'required|unique:zones,name',
            'selectedCounties' => 'array',
            'selectedCounties.*' => 'exists:counties,id',

            'selectedTowns' => 'array',
            'selectedTowns.*' => 'exists:towns,id',
        ]);

        // $assignedCounties = ZoneCounty::whereIn('county_id', $this->selectedCounties)
        //     ->pluck('county_id')
        //     ->toArray();

        // if (!empty($assignedCounties)) {
        //     $assignedCountyNames = County::whereIn('id', $assignedCounties)
        //         ->pluck('name')
        //         ->implode(', ');

        //     throw ValidationException::withMessages([
        //         'selectedCounties' => "The following counties are already assigned to other zones: {$assignedCountyNames}"
        //     ]);
        // }


        $assignedTowns = ZoneTown::whereIn('town_id', $this->selectedTowns)
            ->pluck('town_id')
            ->toArray();

        if (!empty($assignedTowns)) {
            $assignedTownNames = Town::whereIn('id', $assignedTowns)
                ->pluck('name')
                ->implode(', ');

            throw ValidationException::withMessages([
                'selectedTowns' => "The following towns are already assigned to other zones: {$assignedTownNames}"
            ]);
        }



        try {
            DB::beginTransaction();
            $zone = Zone::create([
                'name' => $this->name,
            ]);

            foreach ($this->selectedTowns as $townId) {
                ZoneTown::create([
                    'zone_id' => $zone->id,
                    'town_id' => $townId,
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
