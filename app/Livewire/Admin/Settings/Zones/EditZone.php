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

class EditZone extends Component
{
    public $zoneId;
    public $name;
    public $counties = [];
    public $towns =  [];
    public $selectedCounties = [];

    public $selectedTowns = [];
    public function mount($id)
    {
        $this->zoneId = $id;
        $zone = Zone::with('towns')->findOrFail($id);

        $this->name = $zone->name;
        $this->counties = County::all();
        $this->towns = Town::all();
        $this->selectedCounties = $zone->counties->pluck('county_id')->toArray();
        $this->selectedTowns = $zone->towns->pluck('town_id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:zones,name,' . $this->zoneId,
            'selectedCounties' => 'array',
            'selectedCounties.*' => 'exists:counties,id',

            'selectedTowns' => 'array',
            'selectedTowns.*' => 'exists:towns,id',
        ]);

        // $assignedCounties = ZoneCounty::whereIn('county_id', $this->selectedCounties)
        //     ->where('zone_id', '!=', $this->zoneId)
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
            ->where('zone_id', '!=', $this->zoneId)
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

            // Update zone
            $zone = Zone::findOrFail($this->zoneId);
            $zone->update([
                'name' => $this->name,
            ]);

            ZoneTown::where('zone_id', $zone->id)->delete();

            foreach ($this->selectedTowns as $townId) {
                ZoneTown::create([
                    'zone_id' => $zone->id,
                    'town_id' => $townId,
                ]);
            }

            DB::commit();

            return redirect()->route('admin.zones.index')->with('success', 'Zone updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error updating zone: ' . $th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.zones.edit-zone');
    }
}
