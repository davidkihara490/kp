<?php

namespace App\Livewire\Partners\Rates;

use App\Models\Item;
use App\Models\PartnerRate;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditPartnerRates extends Component
{
    public $itemId;
    public $item;

    // Existing rate data keyed by zone pair
    public $rates = []; // [source_zone_id][destination_zone_id] => ['cost' => x, 'id' => y, 'status' => z]

    // Zones
    public $sourceZones = [];
    public $destinationZones = [];

    // Bulk apply
    public $bulkSourceZoneId = '';
    public $bulkDestinationZoneId = '';
    public $bulkCost = '';
    public $showBulkApply = false;

    // Search within zones
    public $zoneSearch = '';

    // Show/hide
    public $showOnlyExisting = false;

    public function mount($id)
    {
        $this->itemId = $id;
        $this->loadItem();
        $this->loadZones();
        $this->loadRates();
    }

    public function loadItem()
    {
        $this->item = Item::findOrFail($this->itemId);
    }

    public function loadZones()
    {
        $this->sourceZones = Zone::orderBy('name')
            ->get();

        $this->destinationZones = Zone::orderBy('name')
            ->get();
    }

    public function loadRates()
    {
        $partner = Auth::guard('partner')->user()->partner;

        $existing = PartnerRate::where('partner_id', $partner->id)
            ->where('item_id', $this->itemId)
            ->get();

        $this->rates = [];
        foreach ($existing as $rate) {
            if (!isset($this->rates[$rate->source_zone_id])) {
                $this->rates[$rate->source_zone_id] = [];
            }
            $this->rates[$rate->source_zone_id][$rate->destination_zone_id] = [
                'id' => $rate->id,
                'cost' => $rate->cost,
                'status' => $rate->status,
            ];
        }
    }

    public function getRate($sourceZoneId, $destinationZoneId)
    {
        return $this->rates[$sourceZoneId][$destinationZoneId]['cost'] ?? null;
    }

    public function setRate($sourceZoneId, $destinationZoneId, $cost)
    {
        if (!isset($this->rates[$sourceZoneId])) {
            $this->rates[$sourceZoneId] = [];
        }

        if ($cost === '' || $cost === null) {
            unset($this->rates[$sourceZoneId][$destinationZoneId]);
        } else {
            $this->rates[$sourceZoneId][$destinationZoneId] = [
                'id' => $this->rates[$sourceZoneId][$destinationZoneId]['id'] ?? null,
                'cost' => (float) $cost,
                'status' => $this->rates[$sourceZoneId][$destinationZoneId]['status'] ?? 'active',
            ];
        }
    }

    public function updatedBulkCost()
    {
        // No-op, just for reactivity
    }

    public function applyBulkCost()
    {
        $this->validate([
            'bulkCost' => 'required|numeric|min:0',
        ], [
            'bulkCost.required' => 'Please enter a cost.',
            'bulkCost.numeric' => 'Cost must be a number.',
            'bulkCost.min' => 'Cost cannot be negative.',
        ]);

        $cost = (float) $this->bulkCost;

        if ($this->bulkSourceZoneId && $this->bulkDestinationZoneId) {
            // Apply to specific zone pair
            $this->setRate($this->bulkSourceZoneId, $this->bulkDestinationZoneId, $cost);
            session()->flash('info', "Cost applied to " . $this->getZoneName($this->bulkSourceZoneId) . " → " . $this->getZoneName($this->bulkDestinationZoneId));
        } elseif ($this->bulkSourceZoneId) {
            // Apply to all destinations from a source
            foreach ($this->destinationZones as $dest) {
                $this->setRate($this->bulkSourceZoneId, $dest->id, $cost);
            }
            session()->flash('info', "Cost applied to all destinations from " . $this->getZoneName($this->bulkSourceZoneId));
        } elseif ($this->bulkDestinationZoneId) {
            // Apply to all sources to a destination
            foreach ($this->sourceZones as $src) {
                $this->setRate($src->id, $this->bulkDestinationZoneId, $cost);
            }
            session()->flash('info', "Cost applied to all sources to " . $this->getZoneName($this->bulkDestinationZoneId));
        } else {
            // Apply to all
            foreach ($this->sourceZones as $src) {
                foreach ($this->destinationZones as $dest) {
                    $this->setRate($src->id, $dest->id, $cost);
                }
            }
            session()->flash('info', "Cost applied to all zone combinations.");
        }

        $this->bulkCost = '';
        $this->showBulkApply = false;
    }

    public function clearAllRates()
    {
        $this->rates = [];
        session()->flash('info', 'All rate values cleared. Click Save to persist.');
    }

    protected function getZoneName($zoneId)
    {
        $zone = $this->sourceZones->firstWhere('id', $zoneId)
            ?? $this->destinationZones->firstWhere('id', $zoneId);
        return $zone ? $zone->name : 'Unknown';
    }

    public function save()
    {
        $partner = Auth::guard('partner')->user()->partner;

        // Validate all rates
        $this->validate([
            'rates' => 'array',
        ]);

        // Validate individual costs
        foreach ($this->rates as $sourceId => $destinations) {
            foreach ($destinations as $destId => $data) {
                if (isset($data['cost']) && (!is_numeric($data['cost']) || $data['cost'] < 0)) {
                    session()->flash('error', "Invalid cost for zone pair {$sourceId} → {$destId}.");
                    return;
                }
            }
        }

        try {
            DB::beginTransaction();

            $existingRates = PartnerRate::where('partner_id', $partner->id)
                ->where('item_id', $this->itemId)
                ->get()
                ->keyBy(fn($r) => $r->source_zone_id . '_' . $r->destination_zone_id);

            $processedKeys = [];

            // Upsert submitted rates
            foreach ($this->rates as $sourceId => $destinations) {
                foreach ($destinations as $destId => $data) {
                    if (!isset($data['cost'])) {
                        continue;
                    }

                    $key = $sourceId . '_' . $destId;
                    $processedKeys[] = $key;

                    PartnerRate::updateOrCreate(
                        [
                            'partner_id' => $partner->id,
                            'item_id' => $this->itemId,
                            'source_zone_id' => $sourceId,
                            'destination_zone_id' => $destId,
                        ],
                        [
                            'cost' => $data['cost'],
                            'status' => 'active',
                        ]
                    );
                }
            }

            // Delete rates that were removed
            foreach ($existingRates as $key => $rate) {
                if (!in_array($key, $processedKeys)) {
                    $rate->delete();
                }
            }

            DB::commit();

            session()->flash('success', 'Rates updated successfully for ' . $this->item->name);
            $this->loadRates();

            $this->dispatch('ratesUpdated', $this->itemId);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to update rates: ' . $e->getMessage());
        }
    }

    public function deleteAllRates()
    {
        $partner = Auth::guard('partner')->user()->partner;

        try {
            DB::beginTransaction();

            PartnerRate::where('partner_id', $partner->id)
                ->where('item_id', $this->itemId)
                ->delete();

            DB::commit();

            $this->rates = [];
            session()->flash('success', 'All rates deleted for ' . $this->item->name);
            $this->loadRates();
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Failed to delete rates: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.partners.rates.edit-partner-rates');
    }
}