<?php

namespace App\Livewire\Partners\Earnings;

use App\Models\ParcelPayout;
use App\Models\Partner;
use App\Models\Parcel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

class Earnings extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    #[Url]
    public $search = '';
    #[Url]
    public $statusFilter = '';
    #[Url]
    public $typeFilter = '';
    #[Url]
    public $dateFrom = '';
    #[Url]
    public $dateTo = '';
    #[Url]
    public $sortField = 'created_at';
    #[Url]
    public $sortDirection = 'desc';
    
    public $partner;
    public $partnerType;
    public $selectedPayouts = [];
    public $selectAll = false;
    public $showBulkActions = false;
    
    // Modal properties
    public $showModal = false;
    public $selectedPayout = null;
    public $modalPayout = null;
    
    // Chart visibility
    public $showChart = false;
    
    // Statistics
    public $overallTotal = 0;
    public $pendingTotal = 0;
    public $approvedTotal = 0;
    public $completedTotal = 0;
    public $cancelledTotal = 0;
    public $pendingCount = 0;
    public $completedCount = 0;
    public $cancelledCount = 0;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];
    
    public function mount()
    {
        $this->partner = Auth::guard('partner')->user()->partner ?? 
                        Auth::guard('partner')->user()->driver?->partner ?? 
                        Auth::guard('partner')->user()->parcelHandlingAssistant?->partner;
        $this->partnerType = $this->partner->partner_type ?? 'unknown';
        $this->loadStatistics();
    }
    
    public function loadStatistics()
    {
        $query = ParcelPayout::where('partner_id', $this->partner->id);
        
        $this->overallTotal = (clone $query)->sum('amount');
        $this->pendingTotal = (clone $query)->where('status', 'pending')->sum('amount');
        $this->approvedTotal = (clone $query)->where('status', 'approved')->sum('amount');
        $this->completedTotal = (clone $query)->where('status', 'completed')->sum('amount');
        $this->cancelledTotal = (clone $query)->where('status', 'cancelled')->sum('amount');
        
        $this->pendingCount = (clone $query)->where('status', 'pending')->count();
        $this->completedCount = (clone $query)->where('status', 'completed')->count();
        $this->cancelledCount = (clone $query)->where('status', 'cancelled')->count();
    }
    
    public function toggleChart()
    {
        $this->showChart = !$this->showChart;
    }
    
    public function viewDetails($payoutId)
    {
        $this->modalPayout = ParcelPayout::with(['parcel', 'partner', 'parcelDestination', 'origin'])
            ->findOrFail($payoutId);
        $this->showModal = true;
        $this->dispatch('openModal');
    }
    
    public function closeModal()
    {
        $this->showModal = false;
        $this->modalPayout = null;
    }
    
    public function render()
    {
        $query = ParcelPayout::with(['parcel', 'partner', 'parcelDestination', 'origin'])
            ->where('partner_id', $this->partner->id);
        
        // Apply filters
        $query = $query->when($this->search, function ($query) {
            $query->whereHas('parcel', function ($q) {
                $q->where('parcel_number', 'like', '%' . $this->search . '%')
                    ->orWhere('sender_name', 'like', '%' . $this->search . '%')
                    ->orWhere('receiver_name', 'like', '%' . $this->search . '%');
            });
        })
        ->when($this->statusFilter, function ($query) {
            $query->where('status', $this->statusFilter);
        })
        ->when($this->typeFilter, function ($query) {
            $query->where('type', $this->typeFilter);
        })
        ->when($this->dateFrom, function ($query) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        })
        ->when($this->dateTo, function ($query) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        });
        
        $payouts = $query->orderBy($this->sortField, $this->sortDirection)->paginate(15);
        
        // Calculate monthly earnings for chart
        $monthlyEarnings = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyEarnings[] = ParcelPayout::where('partner_id', $this->partner->id)
                ->where('status', 'completed')
                ->whereMonth('created_at', $i)
                ->whereYear('created_at', date('Y'))
                ->sum('amount');
        }
        
        return view('livewire.partners.earnings.earnings', [
            'payouts' => $payouts,
            'monthlyEarnings' => $monthlyEarnings,
            'statuses' => [
                '' => 'All Status',
                'pending' => 'Pending',
                'approved' => 'Approved',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ],
            'payoutTypes' => [
                '' => 'All Types',
                'pickup' => 'Pickup Fee',
                'delivery' => 'Delivery Fee',
                'transport' => 'Transport Fee',
                'commission' => 'Commission',
            ],
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
    
    public function resetFilters()
    {
        $this->search = '';
        $this->statusFilter = '';
        $this->typeFilter = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->selectedPayouts = [];
        $this->selectAll = false;
        $this->showBulkActions = false;
    }
    
    public function updatedSelectAll($value)
    {
        if ($value) {
            $query = ParcelPayout::where('partner_id', $this->partner->id);
            
            if ($this->statusFilter) {
                $query->where('status', $this->statusFilter);
            }
            
            $this->selectedPayouts = $query->pluck('id')->map(fn($id) => (string)$id)->toArray();
        } else {
            $this->selectedPayouts = [];
        }
        $this->showBulkActions = count($this->selectedPayouts) > 0;
    }
    
    public function updatedSelectedPayouts()
    {
        $this->selectAll = false;
        $this->showBulkActions = count($this->selectedPayouts) > 0;
    }
    
    public function hasActiveFilters()
    {
        return $this->search ||
            $this->statusFilter ||
            $this->typeFilter ||
            $this->dateFrom ||
            $this->dateTo;
    }
    
    public function getStatusBadge($status)
    {
        $badges = [
            'pending' => ['color' => '#f59e0b', 'text' => 'Pending', 'icon' => 'bi-clock'],
            'approved' => ['color' => '#3b82f6', 'text' => 'Approved', 'icon' => 'bi-check-circle'],
            'completed' => ['color' => '#10b981', 'text' => 'Completed', 'icon' => 'bi-check-circle-fill'],
            'cancelled' => ['color' => '#ef4444', 'text' => 'Cancelled', 'icon' => 'bi-x-circle'],
        ];
        
        return $badges[$status] ?? ['color' => '#6b7280', 'text' => ucfirst($status), 'icon' => 'bi-question-circle'];
    }
    
    public function getTypeBadge($type)
    {
        $badges = [
            'pickup' => ['color' => '#3b82f6', 'text' => 'Pickup Fee', 'icon' => 'bi-box-arrow-in-down'],
            'delivery' => ['color' => '#10b981', 'text' => 'Delivery Fee', 'icon' => 'bi-box-arrow-up'],
            'transport' => ['color' => '#8b5cf6', 'text' => 'Transport Fee', 'icon' => 'bi-truck'],
            'commission' => ['color' => '#f59e0b', 'text' => 'Commission', 'icon' => 'bi-percent'],
        ];
        
        return $badges[$type] ?? ['color' => '#6b7280', 'text' => ucfirst($type), 'icon' => 'bi-cash'];
    }
    
    public function downloadReport()
    {
        session()->flash('info', 'Report download will be available soon.');
    }
    
    public function requestPayout()
    {
        session()->flash('success', 'Payout request submitted successfully.');
    }
}