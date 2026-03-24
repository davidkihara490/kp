<?php

namespace App\Livewire\Admin\Settings\Pricing;

use App\Models\Item;
use App\Models\Pricing;
use App\Models\WeightRange;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreatePricing extends Component
{
    public $items = [];
    public $weightRanges = [];
    public $zones = [];
    // Form fields
    public $selected_item_id;
    public $selected_weight_range_id;
    public $pricing_rows = [];

    protected $rules = [
        'selected_item_id' => 'required|exists:items,id',
        'selected_weight_range_id' => 'required',
        'pricing_rows.*.source_zone_id' => 'required|exists:zones,id',
        'pricing_rows.*.destination_zone_id' => 'required|exists:zones,id',

        // 'pricing_rows.*.destination_zone_id' => 'required|exists:zones,id|different:pricing_rows.*.source_zone_id',
        'pricing_rows.*.cost' => 'required|numeric|min:0',
    ];

    protected $messages = [
        'pricing_rows.*.source_zone_id.required' => 'The source zone is required.',
        'pricing_rows.*.destination_zone_id.required' => 'The destination zone is required.',
        // 'pricing_rows.*.destination_zone_id.different' => 'Source and destination zones must be different.',
        'pricing_rows.*.cost.required' => 'The cost is required.',
        'pricing_rows.*.cost.numeric' => 'The cost must be a number.',
        'pricing_rows.*.cost.min' => 'The cost must be at least 0.',
    ];

    public function mount()
    {
        $this->items = Item::all();
        $this->weightRanges = WeightRange::all();
        $this->zones = Zone::all();

        // Initialize with one empty row
        $this->addPricingRow();
    }

    public function addPricingRow()
    {
        $this->pricing_rows[] = [
            'source_zone_id' => '',
            'destination_zone_id' => '',
            'cost' => '',
        ];
    }

    public function removePricingRow($index)
    {
        unset($this->pricing_rows[$index]);
        $this->pricing_rows = array_values($this->pricing_rows);
    }

    public function submit()
    {
        $this->validate();

        try {
            $weightRange = WeightRange::findOrFail($this->selected_weight_range_id);

            DB::beginTransaction();
            $pricing = Pricing::create([
                'item_id' => $this->selected_item_id,
                'min_weight' => $weightRange->min_weight,
                'max_weight' => $weightRange->max_weight,

            ]);
            $pricing->items()->createMany($this->pricing_rows);

            DB::commit();

            return redirect()->route('admin.pricing.index')->with('success', 'Pricing created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating pricing: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.settings.pricing.create-pricing');
    }
}
