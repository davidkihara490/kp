<?php

namespace App\Livewire\Admin\Settings\Zones;

use App\Models\County;
use App\Models\Zone;
use App\Models\ZoneCounty;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class EditZone extends Component
{
    public $zoneId;
    public $name;
    public $counties = [];
    public $selectedCounties = [];

    public function mount($id)
    {
        $this->zoneId = $id;
        $zone = Zone::with('counties')->findOrFail($id);

        $this->name = $zone->name;
        $this->counties = County::all();
        $this->selectedCounties = $zone->counties->pluck('county_id')->toArray();
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|unique:zones,name,' . $this->zoneId,
            'selectedCounties' => 'array',
            'selectedCounties.*' => 'exists:counties,id',
        ]);

        $assignedCounties = ZoneCounty::whereIn('county_id', $this->selectedCounties)
            ->where('zone_id', '!=', $this->zoneId)
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

            // Update zone
            $zone = Zone::findOrFail($this->zoneId);
            $zone->update([
                'name' => $this->name,
            ]);

            // Delete existing county associations
            ZoneCounty::where('zone_id', $zone->id)->delete();

            // Create new county associations
            foreach ($this->selectedCounties as $countyId) {
                ZoneCounty::create([
                    'zone_id' => $zone->id,
                    'county_id' => $countyId,
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
