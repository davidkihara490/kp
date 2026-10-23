<?php

namespace App\Livewire\Partners\Rates;

use App\Models\Item;
use App\Models\PartnerRate;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerRates extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search = '';
    public $statusFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $rateFilter = ''; // 'with_rates', 'without_rates', ''

    public $showDeleteModal = false;
    public $rateToDelete = null;
    public $showBulkActions = false;
    public $selectedItems = [];
    public $selectAll = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'rateFilter' => ['except' => ''],
        'sortField' => ['except' => 'name'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Ensure partner is authenticated
        if (!Auth::guard('partner')->check()) {
            abort(403);
        }
    }

    public function render()
    {
        $partner = Auth::guard('partner')->user()->partner;

        // Get partner rate item IDs for filtering
        $partnerRateItemIds = PartnerRate::where('partner_id', $partner->id)
            ->pluck('item_id')
            ->unique()
            ->toArray();

        // Base query
        $query = Item::query()
            ->when($this->search, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->rateFilter === 'with_rates', function ($q) use ($partnerRateItemIds) {
                $q->whereIn('id', $partnerRateItemIds);
            })
            ->when($this->rateFilter === 'without_rates', function ($q) use ($partnerRateItemIds) {
                $q->whereNotIn('id', $partnerRateItemIds);
            });

        // Paginated items
        $items = (clone $query)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);

        // Count stats
        $totalItems = (clone $query)->count();
        $itemsWithRates = Item::whereIn('id', $partnerRateItemIds)->count();
        $itemsWithoutRates = Item::whereNotIn('id', $partnerRateItemIds)->count();

        // Get rate counts per item for the current partner
        $rateCounts = PartnerRate::where('partner_id', $partner->id)
            ->select('item_id', DB::raw('COUNT(*) as total'))
            ->groupBy('item_id')
            ->pluck('total', 'item_id')
            ->toArray();
            
        return view('livewire.partners.rates.partner-rates', [
            'items' => $items,
            'partnerRateItemIds' => $partnerRateItemIds,
            'rateCounts' => $rateCounts,
            'statuses' => [
                '' => 'All Status',
                'active' => 'Active',
                'inactive' => 'Inactive',
            ],
            'totalItems' => $totalItems,
            'itemsWithRates' => $itemsWithRates,
            'itemsWithoutRates' => $itemsWithoutRates,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function hasRates($itemId)
    {
        $partner = Auth::guard('partner')->user()->partner;
        return PartnerRate::where('partner_id', $partner->id)
            ->where('item_id', $itemId)
            ->exists();
    }

    public function getRateCount($itemId)
    {
        $partner = Auth::guard('partner')->user()->partner;
        return PartnerRate::where('partner_id', $partner->id)
            ->where('item_id', $itemId)
            ->count();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->rateFilter = '';
        $this->sortField = 'name';
        $this->sortDirection = 'asc';
        $this->selectedItems = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedItems = $this->getFilteredItemIds();
        } else {
            $this->selectedItems = [];
        }
        $this->showBulkActions = count($this->selectedItems) > 0;
    }

    public function updatedSelectedItems()
    {
        $this->selectAll = false;
        $this->showBulkActions = count($this->selectedItems) > 0;
    }

    protected function getFilteredItemIds()
    {
        $partner = Auth::guard('partner')->user()->partner;
        $partnerRateItemIds = PartnerRate::where('partner_id', $partner->id)
            ->pluck('item_id')
            ->unique()
            ->toArray();

        return Item::query()
            ->when($this->search, function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->rateFilter === 'with_rates', function ($q) use ($partnerRateItemIds) {
                $q->whereIn('id', $partnerRateItemIds);
            })
            ->when($this->rateFilter === 'without_rates', function ($q) use ($partnerRateItemIds) {
                $q->whereNotIn('id', $partnerRateItemIds);
            })
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    public function confirmDeleteRates($itemId)
    {
        $this->rateToDelete = $itemId;
        $this->showDeleteModal = true;
    }

    public function deleteRates()
    {
        if ($this->rateToDelete) {
            $partner = Auth::guard('partner')->user()->partner;

            try {
                DB::beginTransaction();

                PartnerRate::where('partner_id', $partner->id)
                    ->where('item_id', $this->rateToDelete)
                    ->delete();

                DB::commit();

                $item = Item::find($this->rateToDelete);
                session()->flash('success', "Rates for '{$item->name}' deleted successfully.");
                $this->showDeleteModal = false;
                $this->rateToDelete = null;
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Failed to delete rates: ' . $e->getMessage());
            }
        }
    }

    public function bulkDeleteRates()
    {
        if (count($this->selectedItems) > 0) {
            $partner = Auth::guard('partner')->user()->partner;

            try {
                DB::beginTransaction();

                PartnerRate::where('partner_id', $partner->id)
                    ->whereIn('item_id', $this->selectedItems)
                    ->delete();

                DB::commit();

                session()->flash('success', count($this->selectedItems) . ' item rates deleted.');
                $this->selectedItems = [];
                $this->selectAll = false;
                $this->showBulkActions = false;
            } catch (\Exception $e) {
                DB::rollBack();
                session()->flash('error', 'Failed to delete rates: ' . $e->getMessage());
            }
        }
    }

    public function getStatusBadge($status)
    {
        return match ($status) {
            'active' => ['color' => 'success', 'text' => 'Active', 'icon' => 'fa-check-circle'],
            'inactive' => ['color' => 'secondary', 'text' => 'Inactive', 'icon' => 'fa-times-circle'],
            default => ['color' => 'info', 'text' => ucfirst($status), 'icon' => 'fa-question-circle']
        };
    }
}