<?php

namespace App\Livewire\Admin\Settings\Pricing;

use App\Models\Item;
use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\PricingZone;
use App\Models\WeightRange;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditPricing extends Component
{
    public $items = [];
    public $weightRanges = [];
    public $zones = [];
    public $pricing;
    // Form fields
    public $selected_item_id;
    public $selected_weight_range_id;
    public $pricing_rows = [];

    protected $rules = [
        'selected_item_id' => 'required|exists:items,id',
        'selected_weight_range_id' => 'required|exists:weight_ranges,id',
        'pricing_rows.*.source_zone_id' => 'required|exists:zones,id',
        'pricing_rows.*.destination_zone_id' => 'required|exists:zones,id',
        'pricing_rows.*.cost' => 'required|numeric|min:0',
        'pricing_rows.*.id' => 'nullable|exists:pricing_items,id',
    ];

    protected $messages = [
        'pricing_rows.*.source_zone_id.required' => 'The source zone is required.',
        'pricing_rows.*.destination_zone_id.required' => 'The destination zone is required.',
        'pricing_rows.*.cost.required' => 'The cost is required.',
        'pricing_rows.*.cost.numeric' => 'The cost must be a number.',
        'pricing_rows.*.cost.min' => 'The cost must be at least 0.',
    ];

    public function mount($id)
    {
        $this->pricing = Pricing::with('items')->findOrFail($id);
        
        $this->items = Item::all();
        $this->weightRanges = WeightRange::all();
        $this->zones = Zone::all();

        // Set the main pricing fields
        $this->selected_item_id = $this->pricing->item_id;
        
        // Find the weight range ID based on min_weight and max_weight
        $weightRange = WeightRange::where('min_weight', $this->pricing->min_weight)
            ->where('max_weight', $this->pricing->max_weight)
            ->first();
        
        $this->selected_weight_range_id = $weightRange ? $weightRange->id : null;

        // Load existing pricing zones
        $this->pricing_rows = $this->pricing->items->map(function($zone) {
            return [
                'id' => $zone->id,
                'source_zone_id' => $zone->source_zone_id,
                'destination_zone_id' => $zone->destination_zone_id,
                'cost' => $zone->cost,
            ];
        })->toArray();

        // If no rows exist, add one empty row
        if (empty($this->pricing_rows)) {
            $this->addPricingRow();
        }
    }

    public function addPricingRow()
    {
        $this->pricing_rows[] = [
            'source_zone_id' => '',
            'destination_zone_id' => '',
            'cost' => '',
            'id' => null,
        ];
    }

    public function removePricingRow($index)
    {
        $row = $this->pricing_rows[$index];
        
        // If the row has an ID, mark it for deletion
        if (isset($row['id']) && $row['id']) {
            try {
                Zone::find($row['id'])?->delete();
            } catch (\Exception $e) {
                session()->flash('error', 'Error deleting pricing zone: ' . $e->getMessage());
                return;
            }
        }
        
        unset($this->pricing_rows[$index]);
        $this->pricing_rows = array_values($this->pricing_rows);
    }

    public function submit()
    {
        $this->validate();

        try {
            $weightRange = WeightRange::findOrFail($this->selected_weight_range_id);

            DB::beginTransaction();

            // Update main pricing record
            $this->pricing->update([
                'item_id' => $this->selected_item_id,
                'min_weight' => $weightRange->min_weight,
                'max_weight' => $weightRange->max_weight,
            ]);

            // Get existing zone IDs to track deletions
            $existingZoneIds = $this->pricing->items()->pluck('id')->toArray();
            $submittedZoneIds = collect($this->pricing_rows)
                ->pluck('id')
                ->filter()
                ->toArray();

            // Delete zones that were removed
            $zonesToDelete = array_diff($existingZoneIds, $submittedZoneIds);
            if (!empty($zonesToDelete)) {
                PricingItem::whereIn('id', $zonesToDelete)->delete();
            }

            // Update or create pricing zones
            foreach ($this->pricing_rows as $row) {
                if (isset($row['id']) && $row['id']) {
                    // Update existing zone
                    PricingItem::where('id', $row['id'])->update([
                        'source_zone_id' => $row['source_zone_id'],
                        'destination_zone_id' => $row['destination_zone_id'],
                        'cost' => $row['cost'],
                    ]);
                } else {
                    // Create new zone
                    $this->pricing->items()->create([
                        'source_zone_id' => $row['source_zone_id'],
                        'destination_zone_id' => $row['destination_zone_id'],
                        'cost' => $row['cost'],
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('admin.pricing.index')
                ->with('success', 'Pricing updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error updating pricing: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing.edit-pricing');
    }
}