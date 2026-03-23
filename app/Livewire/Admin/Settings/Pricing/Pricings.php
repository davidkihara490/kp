<?php

namespace App\Livewire\Admin\Settings\Pricing;

use App\Models\Pricing;
use App\Models\PricingItem;
use App\Models\Item;
use App\Models\Zone;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Pricings extends Component
{
    use WithPagination;
    
    public string $search = '';
    public string $itemFilter = '';
    public string $zoneFilter = '';
    public $deleteId;
    public $showDeleteModal = false;
    public $showViewModal = false;
    public $showEditModal = false;
    public $selectedPricing;
    public $pricingItems = [];
    
    // Form fields for editing
    public $editId;
    public $editItemId;
    public $editMinWeight;
    public $editMaxWeight;
    public $prices = [];
    public $allZones = [];
    
    protected $paginationTheme = 'bootstrap';
    
    protected $rules = [
        'editItemId' => 'required|exists:items,id',
        'editMinWeight' => 'required|numeric|min:0',
        'editMaxWeight' => 'required|numeric|gt:editMinWeight',
    ];
    
    protected $messages = [
        'editItemId.required' => 'Please select an item category',
        'editMinWeight.required' => 'Minimum weight is required',
        'editMaxWeight.required' => 'Maximum weight is required',
        'editMaxWeight.gt' => 'Maximum weight must be greater than minimum weight',
    ];
    
    public function mount()
    {
        $this->resetFilters();
        $this->allZones = Zone::orderBy('name')->get();
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->itemFilter = '';
        $this->zoneFilter = '';
        $this->resetPage();
    }
    
    public function updatedSearch()
    {
        $this->resetPage();
    }
    
    public function updatedItemFilter()
    {
        $this->resetPage();
    }
    
    public function updatedZoneFilter()
    {
        $this->resetPage();
    }
    
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
        $this->dispatch('show-delete-modal');
    }
    
    public function delete()
    {
        $pricing = Pricing::findOrFail($this->deleteId);
        
        try {
            DB::beginTransaction();
            
            // Delete related pricing items first
            PricingItem::where('pricing_id', $pricing->id)->delete();
            
            // Delete the pricing record
            $pricing->delete();
            
            DB::commit();
            $this->showDeleteModal = false;
            $this->deleteId = null;
            
            session()->flash('success', 'Pricing deleted successfully');
            $this->dispatch('pricing-deleted');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error deleting pricing: ' . $th->getMessage());
        }
    }
    
    public function viewPricing($id)
    {
        $this->selectedPricing = Pricing::with(['item', 'items.sourceZone', 'items.destinationZone'])
            ->findOrFail($id);
        
        // Group pricing items by source zone for display
        $this->pricingItems = [];
        foreach ($this->selectedPricing->items as $item) {
            $sourceZoneId = $item->source_zone_id;
            $destZoneId = $item->destination_zone_id;
            
            if (!isset($this->pricingItems[$sourceZoneId])) {
                $this->pricingItems[$sourceZoneId] = [
                    'zone' => $item->sourceZone,
                    'destinations' => []
                ];
            }
            
            $this->pricingItems[$sourceZoneId]['destinations'][$destZoneId] = [
                'zone' => $item->destinationZone,
                'cost' => $item->cost
            ];
        }
        
        $this->showViewModal = true;
        $this->dispatch('show-view-modal');
    }
    
    public function editPricing($id)
    {
        $this->editId = $id;
        $pricing = Pricing::with(['item', 'items'])->findOrFail($id);
        
        $this->editItemId = $pricing->item_id;
        $this->editMinWeight = $pricing->min_weight;
        $this->editMaxWeight = $pricing->max_weight;
        
        // Initialize prices array with all zones
        $this->prices = [];
        $zones = Zone::orderBy('name')->get();
        
        // First, initialize all combinations as empty
        foreach ($zones as $sourceZone) {
            foreach ($zones as $destZone) {
                if ($sourceZone->id != $destZone->id) {
                    $this->prices[$sourceZone->id][$destZone->id] = null;
                }
            }
        }
        
        // Then fill with existing prices
        foreach ($pricing->items as $item) {
            $this->prices[$item->source_zone_id][$item->destination_zone_id] = $item->cost;
        }
        
        $this->showEditModal = true;
        $this->dispatch('show-edit-modal');
    }
    
    public function createPricing()
    {
        $this->reset(['editId', 'editItemId', 'editMinWeight', 'editMaxWeight']);
        
        // Initialize empty prices matrix
        $this->prices = [];
        $zones = Zone::orderBy('name')->get();
        
        foreach ($zones as $sourceZone) {
            foreach ($zones as $destZone) {
                if ($sourceZone->id != $destZone->id) {
                    $this->prices[$sourceZone->id][$destZone->id] = null;
                }
            }
        }
        
        $this->showEditModal = true;
        $this->dispatch('show-edit-modal');
    }
    
    public function updatePricing()
    {
        $this->validate();
        
        try {
            DB::beginTransaction();
            
            // Update or create pricing
            $pricing = Pricing::updateOrCreate(
                ['id' => $this->editId],
                [
                    'item_id' => $this->editItemId,
                    'min_weight' => $this->editMinWeight,
                    'max_weight' => $this->editMaxWeight,
                ]
            );
            
            // Update pricing items - only save non-null values
            foreach ($this->prices as $sourceZoneId => $destinations) {
                foreach ($destinations as $destZoneId => $cost) {
                    if ($cost !== null && $cost !== '' && $cost >= 0 && $sourceZoneId != $destZoneId) {
                        PricingItem::updateOrCreate(
                            [
                                'pricing_id' => $pricing->id,
                                'source_zone_id' => $sourceZoneId,
                                'destination_zone_id' => $destZoneId,
                            ],
                            ['cost' => $cost]
                        );
                    } elseif ($cost === null || $cost === '') {
                        // Delete if cost is empty or null
                        PricingItem::where('pricing_id', $pricing->id)
                            ->where('source_zone_id', $sourceZoneId)
                            ->where('destination_zone_id', $destZoneId)
                            ->delete();
                    }
                }
            }
            
            DB::commit();
            
            $this->showEditModal = false;
            $this->reset(['editId', 'editItemId', 'editMinWeight', 'editMaxWeight']);
            
            session()->flash('success', 'Pricing ' . ($this->editId ? 'updated' : 'created') . ' successfully');
            $this->dispatch('pricing-updated');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            session()->flash('error', 'Error saving pricing: ' . $th->getMessage());
        }
    }
    
    public function render()
    {
        $pricings = Pricing::with(['item', 'items'])
            ->when($this->search, function ($query) {
                $query->whereHas('item', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->itemFilter, function ($query) {
                $query->where('item_id', $this->itemFilter);
            })
            ->when($this->zoneFilter, function ($query) {
                $query->whereHas('items', function ($q) {
                    $q->where('source_zone_id', $this->zoneFilter)
                        ->orWhere('destination_zone_id', $this->zoneFilter);
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $items = Item::orderBy('name')->get();
        $zones = Zone::orderBy('name')->get();
        
        return view('livewire.admin.settings.pricing.pricings', [
            'pricings' => $pricings,
            'items' => $items,
            'zones' => $zones,
        ]);
    }
}